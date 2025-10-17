<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Supplier;
use App\Models\Sale;
use App\Models\Expense;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user or get existing one
        $user = User::firstOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John Doe',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'phone_number' => '+1234567890',
                'password' => bcrypt('password123'),
                'is_premium' => true,
                'premium_expires_at' => now()->addYear(),
                'theme' => 'light',
                'is_active' => true,
            ]
        );

        // Create some sample suppliers
        $supplier1 = $user->suppliers()->create([
            'name' => 'Tech Supplies Inc',
            'email' => 'contact@techsupplies.com',
            'phone' => '+1234567891',
            'address' => '123 Tech Street, Silicon Valley',
            'notes' => 'Reliable tech supplier',
            'is_active' => true,
        ]);

        $supplier2 = $user->suppliers()->create([
            'name' => 'Office Depot',
            'email' => 'sales@officedepot.com',
            'phone' => '+1234567892',
            'address' => '456 Office Ave, Business District',
            'notes' => 'Office supplies and equipment',
            'is_active' => true,
        ]);

        // Create sample sales
        $user->sales()->createMany([
            [
                'supplier_id' => $supplier1->id,
                'product_name' => 'Laptop Computer',
                'price' => 1200.00,
                'quantity' => 1,
                'commission' => 120.00,
                'feedback' => 'Excellent product quality',
                'commission_paid' => true,
                'sale_date' => now()->subDays(5),
            ],
            [
                'supplier_id' => $supplier2->id,
                'product_name' => 'Office Chair',
                'price' => 250.00,
                'quantity' => 2,
                'commission' => 50.00,
                'feedback' => 'Comfortable and durable',
                'commission_paid' => false,
                'sale_date' => now()->subDays(3),
            ],
            [
                'supplier_id' => $supplier1->id,
                'product_name' => 'Wireless Mouse',
                'price' => 35.00,
                'quantity' => 5,
                'commission' => 17.50,
                'feedback' => 'Good value for money',
                'commission_paid' => false,
                'sale_date' => now()->subDays(1),
            ],
        ]);

        // Create sample expenses
        $user->expenses()->createMany([
            [
                'title' => 'Marketing Campaign',
                'amount' => 500.00,
                'description' => 'Social media advertising',
                'expense_date' => now()->subDays(7),
            ],
            [
                'title' => 'Office Rent',
                'amount' => 1200.00,
                'description' => 'Monthly office space rental',
                'expense_date' => now()->subDays(10),
            ],
            [
                'title' => 'Business Lunch',
                'amount' => 85.00,
                'description' => 'Client meeting at restaurant',
                'expense_date' => now()->subDays(2),
            ],
        ]);
    }
}
