<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_details')->nullable();
            $table->string('id_document')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->enum('status', ['pending', 'active', 'rejected'])->default('pending');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['phone','address','payment_method','payment_details','id_document','category_id','status']);
        });
    }
};