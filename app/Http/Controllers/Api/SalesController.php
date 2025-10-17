<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Sale::with(['supplier'])
            ->where('user_id', $request->user()->id)
            ->orderBy('sale_date', 'desc');

        if ($request->has('month') && $request->has('year')) {
            $query->byMonth($request->month, $request->year);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->byDateRange($request->start_date, $request->end_date);
        }

        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->has('commission_paid')) {
            $query->where('commission_paid', $request->boolean('commission_paid'));
        }

        // Check if pagination is requested
        if ($request->has('per_page') && $request->get('per_page') !== 'all') {
            $sales = $query->paginate($request->get('per_page', 15));
            return response()->json([
                'success' => true,
                'data' => $sales->items(),
                'pagination' => [
                    'current_page' => $sales->currentPage(),
                    'per_page' => $sales->perPage(),
                    'total' => $sales->total(),
                    'last_page' => $sales->lastPage(),
                ]
            ]);
        }

        // Return all results as simple array
        $sales = $query->get();

        return response()->json([
            'success' => true,
            'data' => $sales
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'commission' => 'required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'feedback' => 'nullable|string',
            'commission_paid' => 'boolean',
            'sale_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $sale = Sale::create([
            'user_id' => $request->user()->id,
            'supplier_id' => $request->supplier_id,
            'product_name' => $request->product_name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'commission' => $request->commission,
            'feedback' => $request->feedback,
            'commission_paid' => $request->boolean('commission_paid', false),
            'sale_date' => $request->sale_date,
        ]);

        $sale->load('supplier');

        return response()->json([
            'success' => true,
            'message' => 'Sale created successfully',
            'data' => $sale
        ], 201);
    }

    public function show(Request $request, Sale $sale): JsonResponse
    {
        if ($sale->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Sale not found'
            ], 404);
        }

        $sale->load('supplier');

        return response()->json([
            'success' => true,
            'data' => $sale
        ]);
    }

    public function update(Request $request, Sale $sale): JsonResponse
    {
        if ($sale->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Sale not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'product_name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:1',
            'commission' => 'sometimes|required|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'feedback' => 'nullable|string',
            'commission_paid' => 'boolean',
            'sale_date' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $sale->update($request->only([
            'supplier_id',
            'product_name',
            'price',
            'quantity',
            'commission',
            'feedback',
            'commission_paid',
            'sale_date',
        ]));

        $sale->load('supplier');

        return response()->json([
            'success' => true,
            'message' => 'Sale updated successfully',
            'data' => $sale
        ]);
    }

    public function destroy(Request $request, Sale $sale): JsonResponse
    {
        if ($sale->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Sale not found'
            ], 404);
        }

        $sale->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sale deleted successfully'
        ]);
    }

    public function markCommissionPaid(Request $request, Sale $sale): JsonResponse
    {
        if ($sale->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Sale not found'
            ], 404);
        }

        $sale->update(['commission_paid' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Commission marked as paid',
            'data' => $sale
        ]);
    }

    /**
     * Mark multiple commissions as paid
     */
    public function markMultipleCommissionsPaid(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'sale_ids' => 'required|array',
            'sale_ids.*' => 'required|integer|exists:sales,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        
        // Update only sales that belong to this user
        $updated = Sale::where('user_id', $user->id)
            ->whereIn('id', $request->sale_ids)
            ->update(['commission_paid' => true]);

        return response()->json([
            'success' => true,
            'message' => "Marked {$updated} commission(s) as paid",
            'updated_count' => $updated
        ]);
    }

    /**
     * Mark all commissions for a supplier as paid
     */
    public function markSupplierCommissionsPaid(Request $request, $supplierId): JsonResponse
    {
        $user = $request->user();

        // Verify supplier exists or allow null for "No Supplier"
        if ($supplierId !== 'null' && $supplierId !== '0') {
            $supplier = \App\Models\Supplier::where('id', $supplierId)
                ->where('user_id', $user->id)
                ->first();

            if (!$supplier) {
                return response()->json([
                    'success' => false,
                    'message' => 'Supplier not found'
                ], 404);
            }
        }

        // Update sales for this supplier
        $query = Sale::where('user_id', $user->id)
            ->where('commission_paid', false);

        if ($supplierId === 'null' || $supplierId === '0') {
            $query->whereNull('supplier_id');
        } else {
            $query->where('supplier_id', $supplierId);
        }

        $updated = $query->update(['commission_paid' => true]);

        return response()->json([
            'success' => true,
            'message' => "Marked {$updated} commission(s) as paid for this supplier",
            'updated_count' => $updated
        ]);
    }
}
