<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // User relationship - sesuai controller
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Arisan group relationship - sesuai controller menggunakan 'arisan_group_id'
            $table->foreignId('arisan_group_id')->constrained('arisan_groups')->onDelete('cascade');
            
            // Payment details
            $table->decimal('amount', 15, 2);
            $table->string('period', 7); // Format: YYYY-MM (sesuai controller)
            
            // Payment status - sesuai controller menggunakan 'payment_status'
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'challenge', 'refunded'])
                  ->default('pending');
            
            // Midtrans fields - sesuai controller
            $table->string('midtrans_order_id')->unique();
            $table->string('transaction_status')->nullable();
            $table->string('fraud_status')->nullable();
            
            // Additional fields - sesuai controller
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['user_id', 'payment_status']);
            $table->index(['arisan_group_id', 'period']);
            $table->index(['midtrans_order_id']);
            
            // Unique constraint untuk mencegah duplicate payment per user per group per period
            $table->unique(['user_id', 'arisan_group_id', 'period'], 'unique_user_group_period');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};