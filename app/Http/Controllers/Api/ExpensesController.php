<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ExpensesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Expense::where('user_id', $request->user()->id)
            ->orderBy('expense_date', 'desc');

        if ($request->has('month') && $request->has('year')) {
            $query->byMonth($request->month, $request->year);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        // Check if pagination is requested
        if ($request->has('per_page') && $request->get('per_page') !== 'all') {
            $expenses = $query->paginate($request->get('per_page', 15));
            return response()->json([
                'success' => true,
                'data' => $expenses->items(),
                'pagination' => [
                    'current_page' => $expenses->currentPage(),
                    'per_page' => $expenses->perPage(),
                    'total' => $expenses->total(),
                    'last_page' => $expenses->lastPage(),
                ]
            ]);
        }

        // Return all results as simple array
        $expenses = $query->get();

        return response()->json([
            'success' => true,
            'data' => $expenses
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $expense = Expense::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'amount' => $request->amount,
            'description' => $request->description,
            'expense_date' => $request->expense_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Expense created successfully',
            'data' => $expense
        ], 201);
    }

    public function show(Request $request, Expense $expense): JsonResponse
    {
        if ($expense->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Expense not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $expense
        ]);
    }

    public function update(Request $request, Expense $expense): JsonResponse
    {
        if ($expense->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Expense not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $expense->update($request->only([
            'title',
            'amount',
            'description',
            'expense_date',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Expense updated successfully',
            'data' => $expense
        ]);
    }

    public function destroy(Request $request, Expense $expense): JsonResponse
    {
        if ($expense->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Expense not found'
            ], 404);
        }

        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Expense deleted successfully'
        ]);
    }
}
