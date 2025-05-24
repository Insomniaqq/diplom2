<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Order;
use App\Models\PurchaseRequest;
use Carbon\Carbon;

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

    public function monthlyNorms()
    {
        $departments = \App\Models\Department::with(['materials', 'distributions' => function($query) {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }])->get();

        $reportData = [];
        foreach ($departments as $department) {
            foreach ($department->materials as $material) {
                $monthlyNorm = $material->pivot->monthly_quantity;
                $distributedQuantity = $department->distributions
                    ->where('material_id', $material->id)
                    ->sum('quantity');

                $reportData[] = [
                    'department_name' => $department->name,
                    'material_name' => $material->name,
                    'monthly_norm' => $monthlyNorm,
                    'distributed' => $distributedQuantity,
                    'remaining' => max(0, $monthlyNorm - $distributedQuantity),
                    'percentage' => $monthlyNorm > 0 ? 
                        min(100, round(($distributedQuantity / $monthlyNorm) * 100)) : 0
                ];
            }
        }

        return view('reports.monthly_norms', compact('reportData'));
    }

    public function getDashboardStats()
    {
        // Get data for the last 6 months
        $months = collect([]);
        $requests = collect([]);
        $orders = collect([]);

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months->push($month->translatedFormat('F Y')); // Format: 'May 2023'

            $requestsCount = PurchaseRequest::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $ordersCount = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $requests->push($requestsCount);
            $orders->push($ordersCount);
        }

        return response()->json([
            'labels' => $months,
            'requests' => $requests,
            'orders' => $orders,
        ]);
    }
}
