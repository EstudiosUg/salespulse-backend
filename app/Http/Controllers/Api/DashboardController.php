<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get complete dashboard data in one call
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Get overview data
        $totalSales = Sale::where('user_id', $user->id)
            ->byMonth($month, $year)
            ->sum(DB::raw('price * quantity'));

        $totalExpenses = Expense::where('user_id', $user->id)
            ->byMonth($month, $year)
            ->sum('amount');

        $totalProducts = Sale::where('user_id', $user->id)
            ->byMonth($month, $year)
            ->sum('quantity');

        $commissionPaid = Sale::where('user_id', $user->id)
            ->byMonth($month, $year)
            ->where('commission_paid', true)
            ->sum('commission');

        $unpaidCommission = Sale::where('user_id', $user->id)
            ->where('commission_paid', false)
            ->sum('commission');

        // Get unpaid commissions grouped by supplier with product details
        $unpaidSales = Sale::with('supplier')
            ->where('user_id', $user->id)
            ->where('commission_paid', false)
            ->where('commission', '>', 0)
            ->orderBy('sale_date', 'desc')
            ->get();

        $unpaidCommissionsList = $unpaidSales->groupBy(function ($sale) {
            return $sale->supplier_id ?? 'no_supplier';
        })->map(function ($sales, $groupKey) {
            $firstSale = $sales->first();
            return [
                'supplier_id' => $groupKey !== 'no_supplier' ? $groupKey : null,
                'supplier' => $firstSale->supplier,
                'supplier_name' => $firstSale->supplier ? $firstSale->supplier->name : 'No Supplier',
                'total_commission' => $sales->sum('commission'),
                'sales_count' => $sales->count(),
                'products' => $sales->map(function ($sale) {
                    return [
                        'id' => $sale->id,
                        'product_name' => $sale->product_name,
                        'commission' => $sale->commission,
                        'sale_date' => $sale->sale_date,
                        'quantity' => $sale->quantity,
                        'price' => $sale->price,
                        'total_amount' => $sale->total_amount,
                    ];
                })->values()
            ];
        })->values();

        $hasUnpaidCommissions = $unpaidCommission > 0;

        return response()->json([
            'success' => true,
            'data' => [
                'overview' => [
                    'total_sales' => $totalSales,
                    'total_expenses' => $totalExpenses,
                    'total_products' => $totalProducts,
                    'commission_paid' => $commissionPaid,
                    'unpaid_commission' => $unpaidCommission,
                    'net_profit' => $totalSales - $totalExpenses,
                    'month' => $month,
                    'year' => $year,
                ],
                'unpaid_commissions' => [
                    'has_unpaid' => $hasUnpaidCommissions,
                    'total_unpaid' => $unpaidCommission,
                    'list' => $unpaidCommissionsList,
                ],
            ]
        ]);
    }

    public function overview(Request $request): JsonResponse
    {
        $user = $request->user();
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Get month filter if provided
        $month = $request->get('month', $currentMonth);
        $year = $request->get('year', $currentYear);

        // Total sales for the month
        $totalSales = Sale::where('user_id', $user->id)
            ->byMonth($month, $year)
            ->sum(DB::raw('price * quantity'));

        // Total expenses for the month
        $totalExpenses = Expense::where('user_id', $user->id)
            ->byMonth($month, $year)
            ->sum('amount');

        // Total products sold
        $totalProducts = Sale::where('user_id', $user->id)
            ->byMonth($month, $year)
            ->sum('quantity');

        // Commission paid this month
        $commissionPaid = Sale::where('user_id', $user->id)
            ->byMonth($month, $year)
            ->where('commission_paid', true)
            ->sum('commission');

        // Unpaid commission
        $unpaidCommission = Sale::where('user_id', $user->id)
            ->where('commission_paid', false)
            ->sum('commission');

        return response()->json([
            'success' => true,
            'data' => [
                'total_sales' => $totalSales,
                'total_expenses' => $totalExpenses,
                'total_products' => $totalProducts,
                'commission_paid' => $commissionPaid,
                'unpaid_commission' => $unpaidCommission,
                'net_profit' => $totalSales - $totalExpenses,
                'month' => $month,
                'year' => $year
            ]
        ]);
    }

    public function unpaidCommissions(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get all unpaid sales with supplier info
        $unpaidSales = Sale::with('supplier')
            ->where('user_id', $user->id)
            ->where('commission_paid', false)
            ->where('commission', '>', 0)
            ->get();

        // Group by supplier and calculate totals
        $unpaidCommissions = $unpaidSales->groupBy(function ($sale) {
            return $sale->supplier_id ?? 'no_supplier';
        })->map(function ($sales, $groupKey) {
            $firstSale = $sales->first();
            return [
                'supplier_id' => $groupKey !== 'no_supplier' ? $groupKey : null,
                'supplier' => $firstSale->supplier,
                'supplier_name' => $firstSale->supplier ? $firstSale->supplier->name : 'No Supplier',
                'total_commission' => $sales->sum('commission'),
                'sales_count' => $sales->count(),
                'products' => $sales->map(function ($sale) {
                    return [
                        'id' => $sale->id,
                        'product_name' => $sale->product_name,
                        'commission' => $sale->commission,
                        'sale_date' => $sale->sale_date,
                        'quantity' => $sale->quantity,
                        'price' => $sale->price,
                    ];
                })->values()
            ];
        })->values();

        $totalUnpaid = $unpaidSales->sum('commission');

        return response()->json([
            'success' => true,
            'data' => [
                'has_unpaid' => $totalUnpaid > 0,
                'total_unpaid' => $totalUnpaid,
                'unpaid_commissions' => $unpaidCommissions,
            ]
        ]);
    }

    public function salesExpenseHistory(Request $request): JsonResponse
    {
        $user = $request->user();
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $type = $request->get('type', 'both'); // sales, expenses, or both
        $limit = $request->get('limit'); // Optional limit

        $data = [];

        if ($type === 'sales' || $type === 'both') {
            $salesQuery = Sale::with('supplier')
                ->where('user_id', $user->id)
                ->byMonth($month, $year)
                ->orderBy('sale_date', 'desc');

            if ($limit) {
                $salesQuery->limit($limit);
            }

            $data['sales'] = $salesQuery->get();
        }

        if ($type === 'expenses' || $type === 'both') {
            $expensesQuery = Expense::where('user_id', $user->id)
                ->byMonth($month, $year)
                ->orderBy('expense_date', 'desc');

            if ($limit) {
                $expensesQuery->limit($limit);
            }

            $data['expenses'] = $expensesQuery->get();
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function monthlyStats(Request $request): JsonResponse
    {
        $user = $request->user();
        $year = $request->get('year', now()->year);

        $monthlyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $sales = Sale::where('user_id', $user->id)
                ->byMonth($month, $year)
                ->sum(DB::raw('price * quantity'));

            $expenses = Expense::where('user_id', $user->id)
                ->byMonth($month, $year)
                ->sum('amount');

            $monthlyData[] = [
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'sales' => $sales,
                'expenses' => $expenses,
                'profit' => $sales - $expenses
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $monthlyData
        ]);
    }
}
