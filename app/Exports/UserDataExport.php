<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class UserDataExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $result = collect();

        // Add Sales Records section
        $result->push(['SALES RECORDS']);
        $result->push(['Date', 'Product Name', 'Price', 'Quantity', 'Total Amount', 'Commission', 'Commission Paid', 'Supplier', 'Feedback']);
        
        if (isset($this->data['sales'])) {
            foreach ($this->data['sales'] as $sale) {
                $result->push([
                    $sale->sale_date->format('Y-m-d'),
                    $sale->product_name,
                    $sale->price,
                    $sale->quantity,
                    $sale->price * $sale->quantity,
                    $sale->commission,
                    $sale->commission_paid ? 'Yes' : 'No',
                    $sale->supplier ? $sale->supplier->name : 'N/A',
                    $sale->feedback,
                ]);
            }
        }

        // Add empty row for separation
        $result->push(['']);

        // Add Expense Records section
        $result->push(['EXPENSE RECORDS']);
        $result->push(['Date', 'Title', 'Amount', 'Description']);
        
        if (isset($this->data['expenses'])) {
            foreach ($this->data['expenses'] as $expense) {
                $result->push([
                    $expense->expense_date->format('Y-m-d'),
                    $expense->title,
                    $expense->amount,
                    $expense->description,
                ]);
            }
        }

        // Add empty row for separation
        $result->push(['']);

        // Add Summary section
        $result->push(['SUMMARY']);
        $result->push(['Metric', 'Value']);
        
        $totalSales = 0;
        $totalExpenses = 0;
        $totalCommission = 0;
        $totalProducts = 0;

        // Calculate totals from sales
        if (isset($this->data['sales'])) {
            foreach ($this->data['sales'] as $sale) {
                $totalSales += $sale->price * $sale->quantity;
                $totalCommission += $sale->commission;
                $totalProducts += $sale->quantity;
            }
        }

        // Calculate totals from expenses
        if (isset($this->data['expenses'])) {
            foreach ($this->data['expenses'] as $expense) {
                $totalExpenses += $expense->amount;
            }
        }

        $netIncome = $totalSales - $totalExpenses;

        $result->push(['Total Sales', number_format($totalSales, 2)]);
        $result->push(['Total Expenses', number_format($totalExpenses, 2)]);
        $result->push(['Total Commission', number_format($totalCommission, 2)]);
        $result->push(['Net Income', number_format($netIncome, 2)]);
        $result->push(['Total Products', $totalProducts]);

        return $result;
    }

    public function headings(): array
    {
        return [];
    }
}

