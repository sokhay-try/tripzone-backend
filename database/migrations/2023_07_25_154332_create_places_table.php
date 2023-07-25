<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('name', 250)->unique();
            $table->longText('description');
            $table->integer('visitor')->default(0);
            $table->integer('distance')->default(0);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->longText('address');
            $table->unsignedBigInteger('province_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};
