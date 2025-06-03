<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Contract;
use Illuminate\Http\Request;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Carbon\Carbon;

class SupplierReportController extends Controller
{
    public function download($format)
    {
        // Получаем данные для отчета
        $year = request('year', Carbon::now()->year);
        $month = request('month', Carbon::now()->month);

        $suppliers = Supplier::with(['contracts' => function($query) use ($year) {
            $query->whereYear('created_at', $year);
        }])->get();

        $totalAmount = $suppliers->sum(function($supplier) {
            return $supplier->contracts->sum('amount');
        });

        $data = [
            'suppliers' => $suppliers,
            'totalAmount' => $totalAmount,
            'year' => $year,
            'month' => $month,
            'generatedAt' => Carbon::now()->format('d.m.Y H:i:s'),
        ];

        if ($format === 'pdf') {
            return $this->generatePDF($data);
        } elseif ($format === 'docx') {
            return $this->generateDOCX($data);
        }

        return back()->with('error', 'Неподдерживаемый формат файла');
    }

    private function generatePDF($data)
    {
        $pdf = PDF::loadView('reports.suppliers-pdf', $data);
        $filename = "suppliers_report_{$data['year']}_{$data['month']}.pdf";
        
        return $pdf->download($filename);
    }

    private function generateDOCX($data)
    {
        $phpWord = new PhpWord();
        
        // Добавляем стили
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16]);
        $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 14]);
        
        $section = $phpWord->addSection();
        
        // Заголовок
        $section->addTitle("Отчет по поставщикам за {$data['month']}.{$data['year']}", 1);
        $section->addText("Сгенерировано: {$data['generatedAt']}");
        $section->addTextBreak();
        
        // Таблица с поставщиками
        $table = $section->addTable();
        $table->addRow();
        $table->addCell()->addText('Поставщик', ['bold' => true]);
        $table->addCell()->addText('ИНН', ['bold' => true]);
        $table->addCell()->addText('Количество контрактов', ['bold' => true]);
        $table->addCell()->addText('Сумма контрактов', ['bold' => true]);
        
        foreach ($data['suppliers'] as $supplier) {
            $contractsCount = $supplier->contracts->count();
            $contractsSum = $supplier->contracts->sum('amount');
            
            $table->addRow();
            $table->addCell()->addText($supplier->name);
            $table->addCell()->addText($supplier->inn);
            $table->addCell()->addText($contractsCount);
            $table->addCell()->addText(number_format($contractsSum, 2));
        }
        
        // Итоговая сумма
        $section->addTextBreak();
        $section->addText("Общая сумма контрактов: " . number_format($data['totalAmount'], 2) . " руб.", ['bold' => true]);
        
        // Сохраняем файл
        $filename = "suppliers_report_{$data['year']}_{$data['month']}.docx";
        $tempFile = tempnam(sys_get_temp_dir(), 'suppliers_report');
        
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);
        
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
} 