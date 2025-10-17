<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SuppliersController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $suppliers = Supplier::where('user_id', $request->user()->id)
            ->when($request->has('active'), function ($query) use ($request) {
                return $query->where('is_active', $request->boolean('active'));
            })
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $suppliers
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier = Supplier::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
            'is_active' => $request->boolean('is_active', true)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier created successfully',
            'data' => $supplier
        ], 201);
    }

    public function show(Request $request, Supplier $supplier): JsonResponse
    {
        if ($supplier->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found'
            ], 404);
        }

        $supplier->load('sales');

        return response()->json([
            'success' => true,
            'data' => $supplier
        ]);
    }

    public function update(Request $request, Supplier $supplier): JsonResponse
    {
        if ($supplier->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier->update($request->only([
            'name',
            'email',
            'phone',
            'address',
            'notes',
            'is_active'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully',
            'data' => $supplier
        ]);
    }

    public function destroy(Request $request, Supplier $supplier): JsonResponse
    {
        if ($supplier->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found'
            ], 404);
        }

        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully'
        ]);
    }
}
