<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Seed Admin User
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Seed Normal User
        DB::table('users')->insert([
            'name' => 'Normal User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 2. Seed Customers
        $customerId1 = DB::table('customers')->insertGetId([
            'name' => 'Acme Corporation',
            'email' => 'billing@acme.com',
            'phone' => '+1-555-0199',
            'address' => '123 Industrial Way, Metropolis, NY 10001',
            'tax_number' => 'TAX-US-9921',
            'created_at' => Carbon::now()->subDays(60),
            'updated_at' => Carbon::now()->subDays(60),
        ]);

        $customerId2 = DB::table('customers')->insertGetId([
            'name' => 'Globex Corporation',
            'email' => 'finance@globex.com',
            'phone' => '+1-555-0144',
            'address' => '456 Cyber Tech Blvd, Cypress Creek, CA 95014',
            'tax_number' => 'TAX-US-3342',
            'created_at' => Carbon::now()->subDays(45),
            'updated_at' => Carbon::now()->subDays(45),
        ]);

        $customerId3 = DB::table('customers')->insertGetId([
            'name' => 'Initech Corp',
            'email' => 'accounts@initech.com',
            'phone' => '+1-555-0122',
            'address' => '4120 Freemont Ave, Austin, TX 78701',
            'tax_number' => 'TAX-US-1088',
            'created_at' => Carbon::now()->subDays(30),
            'updated_at' => Carbon::now()->subDays(30),
        ]);

        // 3. Seed Suppliers
        $supplierId1 = DB::table('suppliers')->insertGetId([
            'name' => 'Stark Industries',
            'email' => 'suppliers@stark.com',
            'phone' => '+1-555-3000',
            'address' => '10880 El Segundo Blvd, Los Angeles, CA 90245',
            'tax_number' => 'TAX-STARK-77',
            'created_at' => Carbon::now()->subDays(60),
            'updated_at' => Carbon::now()->subDays(60),
        ]);

        $supplierId2 = DB::table('suppliers')->insertGetId([
            'name' => 'Wayne Enterprises',
            'email' => 'procurement@wayne.com',
            'phone' => '+1-555-1000',
            'address' => '1007 Mountain Drive, Gotham City, NJ 07001',
            'tax_number' => 'TAX-WAYNE-99',
            'created_at' => Carbon::now()->subDays(50),
            'updated_at' => Carbon::now()->subDays(50),
        ]);

        $supplierId3 = DB::table('suppliers')->insertGetId([
            'name' => 'Tyrell Corp',
            'email' => 'replicants@tyrell.com',
            'phone' => '+1-555-2019',
            'address' => 'Tyrell Pyramid, Sector 4, Los Angeles, CA 90012',
            'tax_number' => 'TAX-TYRELL-01',
            'created_at' => Carbon::now()->subDays(40),
            'updated_at' => Carbon::now()->subDays(40),
        ]);

        // 4. Seed Products/Services
        $productId1 = DB::table('products')->insertGetId([
            'name' => 'Software Development Services',
            'sku' => 'SRV-SOFT-DEV',
            'price' => 1500.00,
            'description' => 'Custom software development, programming, and system design per day.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $productId2 = DB::table('products')->insertGetId([
            'name' => 'Cloud Infrastructure Hosting',
            'sku' => 'SRV-CLOUD-HST',
            'price' => 250.00,
            'description' => 'Monthly hosting fees for staging and production servers.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $productId3 = DB::table('products')->insertGetId([
            'name' => 'IT Consulting Services',
            'sku' => 'SRV-IT-CONS',
            'price' => 180.00,
            'description' => 'Hourly professional IT consulting services.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $productId4 = DB::table('products')->insertGetId([
            'name' => 'Office Workstation Desks',
            'sku' => 'PRD-OFF-DESK',
            'price' => 450.00,
            'description' => 'Ergonomic office work desks with cable management.',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 5. Seed Budgets
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        
        $budgetId1 = DB::table('budgets')->insertGetId([
            'category_name' => 'Marketing & Advertising',
            'amount' => 5000.00,
            'start_date' => $currentMonthStart,
            'end_date' => $currentMonthEnd,
            'type' => 'Monthly',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $budgetId2 = DB::table('budgets')->insertGetId([
            'category_name' => 'Office Operations',
            'amount' => 8000.00,
            'start_date' => $currentMonthStart,
            'end_date' => $currentMonthEnd,
            'type' => 'Monthly',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $budgetId3 = DB::table('budgets')->insertGetId([
            'category_name' => 'Software & Subscriptions',
            'amount' => 3000.00,
            'start_date' => $currentMonthStart,
            'end_date' => $currentMonthEnd,
            'type' => 'Monthly',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $budgetId4 = DB::table('budgets')->insertGetId([
            'category_name' => 'Research & Development',
            'amount' => 60000.00,
            'start_date' => Carbon::now()->startOfYear(),
            'end_date' => Carbon::now()->endOfYear(),
            'type' => 'Yearly',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // 6. Seed Expenses against Budgets
        DB::table('expenses')->insert([
            [
                'budget_id' => $budgetId1,
                'description' => 'Google Search Ads Campaign',
                'amount' => 1200.00,
                'date' => Carbon::now()->subDays(10)->toDateString(),
                'supplier_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'budget_id' => $budgetId1,
                'description' => 'Social Media Sponsored Posts',
                'amount' => 850.00,
                'date' => Carbon::now()->subDays(5)->toDateString(),
                'supplier_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'budget_id' => $budgetId2,
                'description' => 'Office Chairs and Ergonomic Supplies',
                'amount' => 2400.00,
                'date' => Carbon::now()->subDays(15)->toDateString(),
                'supplier_id' => $supplierId2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'budget_id' => $budgetId3,
                'description' => 'GitHub Enterprise Annual License',
                'amount' => 3200.00, // Exceeds budget on purpose for visual progress bar warning!
                'date' => Carbon::now()->subDays(8)->toDateString(),
                'supplier_id' => $supplierId1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'budget_id' => $budgetId4,
                'description' => 'AI Model Training Resources (Compute)',
                'amount' => 15000.00,
                'date' => Carbon::now()->subDays(20)->toDateString(),
                'supplier_id' => $supplierId3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        // 7. Seed Sales Invoices
        // Sales Invoice 1: Paid
        $salesInvoiceId1 = DB::table('sales_invoices')->insertGetId([
            'invoice_number' => 'INV-SAL-2026-001',
            'customer_id' => $customerId1,
            'invoice_date' => Carbon::now()->subDays(35)->toDateString(),
            'due_date' => Carbon::now()->subDays(5)->toDateString(),
            'subtotal' => 4500.00,
            'tax_amount' => 450.00, // 10%
            'discount_amount' => 450.00, // 10%
            'total_amount' => 4500.00,
            'status' => 'Paid',
            'notes' => 'Custom software dashboard setup.',
            'created_at' => Carbon::now()->subDays(35),
            'updated_at' => Carbon::now()->subDays(35),
        ]);

        DB::table('invoice_items')->insert([
            'sales_invoice_id' => $salesInvoiceId1,
            'product_id' => $productId1,
            'quantity' => 3,
            'unit_price' => 1500.00,
            'tax_rate' => 10.00,
            'discount_rate' => 10.00,
            'subtotal' => 4500.00,
            'total' => 4500.00,
            'created_at' => Carbon::now()->subDays(35),
            'updated_at' => Carbon::now()->subDays(35),
        ]);

        DB::table('payments')->insert([
            'sales_invoice_id' => $salesInvoiceId1,
            'amount' => 4500.00,
            'payment_date' => Carbon::now()->subDays(6)->toDateString(),
            'payment_method' => 'Bank Transfer',
            'reference_number' => 'TXN-SAL-00921',
            'created_at' => Carbon::now()->subDays(6),
            'updated_at' => Carbon::now()->subDays(6),
        ]);

        // Sales Invoice 2: Pending
        $salesInvoiceId2 = DB::table('sales_invoices')->insertGetId([
            'invoice_number' => 'INV-SAL-2026-002',
            'customer_id' => $customerId2,
            'invoice_date' => Carbon::now()->subDays(10)->toDateString(),
            'due_date' => Carbon::now()->addDays(20)->toDateString(),
            'subtotal' => 1400.00,
            'tax_amount' => 140.00, // 10%
            'discount_amount' => 0.00,
            'total_amount' => 1540.00,
            'status' => 'Pending',
            'notes' => 'Consulting and cloud infrastructure.',
            'created_at' => Carbon::now()->subDays(10),
            'updated_at' => Carbon::now()->subDays(10),
        ]);

        DB::table('invoice_items')->insert([
            [
                'sales_invoice_id' => $salesInvoiceId2,
                'product_id' => $productId3, // IT Consulting
                'quantity' => 5,
                'unit_price' => 180.00,
                'tax_rate' => 10.00,
                'discount_rate' => 0.00,
                'subtotal' => 900.00,
                'total' => 990.00,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
            [
                'sales_invoice_id' => $salesInvoiceId2,
                'product_id' => $productId2, // Cloud Infrastructure
                'quantity' => 2,
                'unit_price' => 250.00,
                'tax_rate' => 10.00,
                'discount_rate' => 0.00,
                'subtotal' => 500.00,
                'total' => 550.00,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
        ]);

        // Sales Invoice 3: Overdue
        $salesInvoiceId3 = DB::table('sales_invoices')->insertGetId([
            'invoice_number' => 'INV-SAL-2026-003',
            'customer_id' => $customerId3,
            'invoice_date' => Carbon::now()->subDays(40)->toDateString(),
            'due_date' => Carbon::now()->subDays(10)->toDateString(),
            'subtotal' => 3000.00,
            'tax_amount' => 300.00,
            'discount_amount' => 100.00,
            'total_amount' => 3200.00,
            'status' => 'Overdue',
            'notes' => 'Development Services for portal upgrade.',
            'created_at' => Carbon::now()->subDays(40),
            'updated_at' => Carbon::now()->subDays(40),
        ]);

        DB::table('invoice_items')->insert([
            'sales_invoice_id' => $salesInvoiceId3,
            'product_id' => $productId1,
            'quantity' => 2,
            'unit_price' => 1500.00,
            'tax_rate' => 10.00,
            'discount_rate' => 3.33,
            'subtotal' => 3000.00,
            'total' => 3200.00,
            'created_at' => Carbon::now()->subDays(40),
            'updated_at' => Carbon::now()->subDays(40),
        ]);

        // 8. Seed Purchase Invoices
        // Purchase Invoice 1: Paid
        $purchaseInvoiceId1 = DB::table('purchase_invoices')->insertGetId([
            'invoice_number' => 'INV-PUR-2026-001',
            'supplier_id' => $supplierId2,
            'invoice_date' => Carbon::now()->subDays(30)->toDateString(),
            'due_date' => Carbon::now()->subDays(15)->toDateString(),
            'subtotal' => 1350.00,
            'tax_amount' => 135.00,
            'discount_amount' => 0.00,
            'total_amount' => 1485.00,
            'status' => 'Paid',
            'notes' => 'Supplier bill for office equipment.',
            'created_at' => Carbon::now()->subDays(30),
            'updated_at' => Carbon::now()->subDays(30),
        ]);

        DB::table('invoice_items')->insert([
            'purchase_invoice_id' => $purchaseInvoiceId1,
            'product_id' => $productId4,
            'quantity' => 3,
            'unit_price' => 450.00,
            'tax_rate' => 10.00,
            'discount_rate' => 0.00,
            'subtotal' => 1350.00,
            'total' => 1485.00,
            'created_at' => Carbon::now()->subDays(30),
            'updated_at' => Carbon::now()->subDays(30),
        ]);

        DB::table('payments')->insert([
            'purchase_invoice_id' => $purchaseInvoiceId1,
            'amount' => 1485.00,
            'payment_date' => Carbon::now()->subDays(20)->toDateString(),
            'payment_method' => 'Bank Transfer',
            'reference_number' => 'TXN-PUR-00122',
            'created_at' => Carbon::now()->subDays(20),
            'updated_at' => Carbon::now()->subDays(20),
        ]);

        // Purchase Invoice 2: Pending
        $purchaseInvoiceId2 = DB::table('purchase_invoices')->insertGetId([
            'invoice_number' => 'INV-PUR-2026-002',
            'supplier_id' => $supplierId1,
            'invoice_date' => Carbon::now()->subDays(5)->toDateString(),
            'due_date' => Carbon::now()->addDays(25)->toDateString(),
            'subtotal' => 3200.00,
            'tax_amount' => 0.00,
            'discount_amount' => 0.00,
            'total_amount' => 3200.00,
            'status' => 'Pending',
            'notes' => 'Cloud licensing billing.',
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(5),
        ]);

        DB::table('invoice_items')->insert([
            'purchase_invoice_id' => $purchaseInvoiceId2,
            'product_id' => $productId2, // Cloud Infrastructure
            'quantity' => 12,
            'unit_price' => 266.67, // $3,200 total approx
            'tax_rate' => 0.00,
            'discount_rate' => 0.00,
            'subtotal' => 3200.00,
            'total' => 3200.00,
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(5),
        ]);
    }
}
