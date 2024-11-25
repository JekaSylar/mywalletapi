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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('balance')->default(0);
            $table->string('currency')->default('₴');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');;
            $table->timestamps();
        });
    }


};
