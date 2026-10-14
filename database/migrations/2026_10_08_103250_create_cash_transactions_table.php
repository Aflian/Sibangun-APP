<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['income', 'expense', 'sale', 'purchase', 'receivable_payment', 'payable_payment', 'transfer', 'adjustment'])->index();
            $table->string('reference_type', 100)->nullable()->index();
            $table->unsignedBigInteger('reference_id')->nullable()->index();
            $table->enum('payment_method', ['cash', 'transfer', 'qris', 'debit'])->default('cash')->index();
            $table->decimal('amount', 15, 2)->default(0);
            $table->dateTime('transaction_date')->index();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};
