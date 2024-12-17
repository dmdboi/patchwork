<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Posts 
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            //Ref
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('author_type')->nullable();

            $table->string('type')->default('post')->nullable();

            //Info
            $table->json('title');
            $table->string('slug')->unique()->index();

            $table->json('short_description')->nullable();
            $table->json('keywords')->nullable();

            $table->json('body')->nullable();

            //Options
            $table->boolean('is_published')->default(0);
            $table->boolean('is_trend')->default(0);
            $table->dateTime('published_at')->nullable();

            //Counters
            $table->double('likes')->default(0);
            $table->double('views')->default(0);

            //Meta
            $table->string('meta_url')->nullable();
            $table->json('meta')->nullable();
            $table->text('meta_redirect')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // Post Metas
        Schema::create('post_metas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('model_type')->nullable();

            $table->foreignId('post_id')->references('id')->on('posts')->onDelete('cascade');

            $table->string('key')->index();
            $table->json('value')->nullable();

            $table->timestamps();
        });

        // Posts Has Tags
        Schema::create('posts_has_tags', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('categories')->onDelete('cascade');
        });


        // Posts Has Category
        Schema::create('posts_has_category', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
