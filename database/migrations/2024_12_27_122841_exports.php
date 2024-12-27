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
        Schema::create("exports", function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->integer("user_id");
            $table->string("exporter");
            $table->integer("total_rows");
            $table->integer("processed_rows");
            $table->string("file_disk");
            $table->string("file_name")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists("exports");
    }
};
