<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $title = $this->faker->text;
        $slug = Str::slug($title);

        return [
            'author_id' => User::factory(),
            'type' => 'post',
            'title' => $title,
            'slug' => $slug,
            'short_description' => $this->faker->text,
            'body' => $this->faker->text,
            'is_published' => $this->faker->boolean,
            'published_at' => $this->faker->dateTime(),
        ];
    }
}
