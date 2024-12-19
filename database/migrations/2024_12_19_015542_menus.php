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
        //
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            //Set Key To Get Menu
            $table->string('key')->unique();

            //Where You Can See The Menu
            $table->string('location')->default('header');

            //Title For The Menu
            $table->string('title');

            //Menu Item As Json
            $table->longText('items')->nullable();

            $table->boolean('is_active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
