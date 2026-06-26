<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_invoice_id')->nullable()->constrained('sales_invoices')->onDelete('cascade');
            $table->foreignId('purchase_invoice_id')->nullable()->constrained('purchase_invoices')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('tax_rate', 5, 2)->default(0.00); // e.g. 18.00 for 18%
            $table->decimal('discount_rate', 5, 2)->default(0.00); // e.g. 10.00 for 10%
            $table->decimal('subtotal', 15, 2);
            $table->decimal('total', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};
