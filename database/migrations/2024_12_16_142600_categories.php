<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id')->nullable()->references('id')->on('categories')->onDelete('cascade');
            $table->string('for')->default('posts')->nullable();
            $table->string('type')->default('category')->nullable();
            $table->json('name');
            $table->string('slug')->unique()->index();
            $table->json('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();

            $table->boolean('is_active')->default(1)->nullable();
            $table->boolean('show_in_menu')->default(0)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // Category Metas
        Schema::create('categories_metas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('model_type')->nullable();

            $table->foreignId('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->string('key')->index();
            $table->json('value')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('categories_metas');
    }
};
