<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialDistribution;
use Illuminate\Http\Request;
use PDF;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Carbon\Carbon;

class BudgetReportController extends Controller
{
    public function download($format)
    {
        // Получаем данные для отчета
        $year = request('year', Carbon::now()->year);
        $month = request('month', Carbon::now()->month);

        $materials = Material::with(['distributions' => function($query) use ($year, $month) {
            $query->whereYear('created_at', $year)
                  ->whereMonth('created_at', $month);
        }])->get();

        $totalCost = $materials->sum(function($material) {
            return $material->distributions->sum('quantity') * $material->price;
        });

        $data = [
            'materials' => $materials,
            'totalCost' => $totalCost,
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
        $pdf = PDF::loadView('reports.budget-pdf', $data);
        $filename = "budget_report_{$data['year']}_{$data['month']}.pdf";
        
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
        $section->addTitle("Отчет по бюджету за {$data['month']}.{$data['year']}", 1);
        $section->addText("Сгенерировано: {$data['generatedAt']}");
        $section->addTextBreak();
        
        // Таблица с материалами
        $table = $section->addTable();
        $table->addRow();
        $table->addCell()->addText('Материал', ['bold' => true]);
        $table->addCell()->addText('Количество', ['bold' => true]);
        $table->addCell()->addText('Цена', ['bold' => true]);
        $table->addCell()->addText('Сумма', ['bold' => true]);
        
        foreach ($data['materials'] as $material) {
            $quantity = $material->distributions->sum('quantity');
            $sum = $quantity * $material->price;
            
            $table->addRow();
            $table->addCell()->addText($material->name);
            $table->addCell()->addText($quantity);
            $table->addCell()->addText(number_format($material->price, 2));
            $table->addCell()->addText(number_format($sum, 2));
        }
        
        // Итоговая сумма
        $section->addTextBreak();
        $section->addText("Общая сумма: " . number_format($data['totalCost'], 2) . " руб.", ['bold' => true]);
        
        // Сохраняем файл
        $filename = "budget_report_{$data['year']}_{$data['month']}.docx";
        $tempFile = tempnam(sys_get_temp_dir(), 'budget_report');
        
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);
        
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
} 