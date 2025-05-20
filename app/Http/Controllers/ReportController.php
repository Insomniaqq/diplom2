<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;

class ReportController extends Controller
{
    public function budget(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $budgets = Budget::where('year', $year)
            ->orderBy('month')
            ->get(['month', 'amount', 'spent']);
        $months = range(1, 12);
        $amounts = array_fill(1, 12, 0);
        $spents = array_fill(1, 12, 0);
        foreach ($budgets as $b) {
            if ($b->month) {
                $amounts[$b->month] = (float)$b->amount;
                $spents[$b->month] = (float)$b->spent;
            }
        }
        return view('reports.budget', compact('year', 'months', 'amounts', 'spents'));
    }

    public function requests(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $requests = 
            \App\Models\PurchaseRequest::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        $months = range(1, 12);
        $counts = array_fill(1, 12, 0);
        foreach ($requests as $r) {
            $counts[$r->month] = $r->count;
        }
        return view('reports.requests', compact('year', 'months', 'counts'));
    }

    public function suppliers(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $suppliers = \App\Models\Supplier::with(['contracts' => function($q) use ($year) {
            $q->whereYear('date_start', $year);
        }])->get();
        $data = [];
        foreach ($suppliers as $supplier) {
            $sum = $supplier->contracts->sum('amount');
            $data[] = [
                'name' => $supplier->name,
                'sum' => $sum,
            ];
        }
        return view('reports.suppliers', compact('year', 'data'));
    }
}
