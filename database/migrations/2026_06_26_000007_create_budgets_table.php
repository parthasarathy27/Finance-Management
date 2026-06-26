<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('category_name');
            $table->decimal('amount', 15, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('type')->default('Monthly'); // Monthly, Yearly
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
};
