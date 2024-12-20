<?php

use App\Filament\Resources\PostResource;
use App\Models\Post;
use App\Models\User;

use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->artisan("migrate:fresh --seed");
    $this->artisan("db:seed --class=RolesAndPermissionsSeeder");

    $this->actingAs(User::query()->where('email', 'admin@example.com')->first());
});

it('can list posts', function () {
    $posts = Post::factory()->count(10)->create();

    livewire(PostResource\Pages\ListPosts::class)
        ->assertCanSeeTableRecords($posts);
});

it('can render page', function () {
    $this->get(PostResource::getUrl('view', [
        'record' => Post::factory()->create(),
    ]))->assertSuccessful();
});

it('can create', function () {
    $newData = Post::factory()->make();

    livewire(PostResource\Pages\CreatePost::class)
        ->fillForm([
            'author_id' => User::first()->getKey(),
            'title' => $newData->title,
            'slug' => Str::slug($newData->title),
            'body' => $newData->body,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Post::class, [
        'author_id' => User::first()->getKey(),
        'title' => $newData->title,
        'slug' => Str::slug($newData->title),
        'body' => $newData->body,
    ]);
});