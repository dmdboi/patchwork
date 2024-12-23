<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

it('creates required tables with migrations', function () {
    // Run the migrations
    $this->artisan('migrate');

    // Assert that the tables are present
    expect(Schema::hasTable('users'))->toBeTrue();
    expect(Schema::hasTable('password_reset_tokens'))->toBeTrue();
    expect(Schema::hasTable('sessions'))->toBeTrue();
    expect(Schema::hasTable('cache'))->toBeTrue();
    expect(Schema::hasTable('cache_locks'))->toBeTrue();
    expect(Schema::hasTable('jobs'))->toBeTrue();
    expect(Schema::hasTable('job_batches'))->toBeTrue();
    expect(Schema::hasTable('failed_jobs'))->toBeTrue();
    expect(Schema::hasTable('collections'))->toBeTrue();
    expect(Schema::hasTable('categories'))->toBeTrue();
    expect(Schema::hasTable('categories_metas'))->toBeTrue();
    expect(Schema::hasTable('posts'))->toBeTrue();
    expect(Schema::hasTable('post_metas'))->toBeTrue();
    expect(Schema::hasTable('posts_has_tags'))->toBeTrue();
    expect(Schema::hasTable('posts_has_category'))->toBeTrue();
    expect(Schema::hasTable('forms'))->toBeTrue();
    expect(Schema::hasTable('form_options'))->toBeTrue();
    expect(Schema::hasTable('form_requests'))->toBeTrue();
    expect(Schema::hasTable('form_request_metas'))->toBeTrue();
    expect(Schema::hasTable('comments'))->toBeTrue();
    expect(Schema::hasTable('menus'))->toBeTrue();
    expect(Schema::hasTable('menu_items'))->toBeTrue();
    expect(Schema::hasTable('roles'))->toBeTrue();
    expect(Schema::hasTable('permissions'))->toBeTrue();
    expect(Schema::hasTable('role_has_permissions'))->toBeTrue();
    expect(Schema::hasTable('model_has_permissions'))->toBeTrue();
    expect(Schema::hasTable('model_has_roles'))->toBeTrue();
    expect(Schema::hasTable('media'))->toBeTrue();
});

it('rolls back migrations correctly', function () {
    // Run the migrations
    $this->artisan('migrate');

    // Rollback the migrations
    $this->artisan('migrate:rollback');

    // Assert that the tables are no longer present
    expect(Schema::hasTable('users'))->toBeFalse();
    expect(Schema::hasTable('password_reset_tokens'))->toBeFalse();
    expect(Schema::hasTable('sessions'))->toBeFalse();
    expect(Schema::hasTable('cache'))->toBeFalse();
    expect(Schema::hasTable('cache_locks'))->toBeFalse();
    expect(Schema::hasTable('jobs'))->toBeFalse();
    expect(Schema::hasTable('job_batches'))->toBeFalse();
    expect(Schema::hasTable('failed_jobs'))->toBeFalse();
    expect(Schema::hasTable('collections'))->toBeFalse();
    expect(Schema::hasTable('categories'))->toBeFalse();
    expect(Schema::hasTable('categories_metas'))->toBeFalse();
    expect(Schema::hasTable('posts'))->toBeFalse();
    expect(Schema::hasTable('post_metas'))->toBeFalse();
    expect(Schema::hasTable('posts_has_tags'))->toBeFalse();
    expect(Schema::hasTable('posts_has_category'))->toBeFalse();
    expect(Schema::hasTable('forms'))->toBeFalse();
    expect(Schema::hasTable('form_options'))->toBeFalse();
    expect(Schema::hasTable('form_requests'))->toBeFalse();
    expect(Schema::hasTable('form_request_metas'))->toBeFalse();
    expect(Schema::hasTable('comments'))->toBeFalse();
    expect(Schema::hasTable('menus'))->toBeFalse();
    expect(Schema::hasTable('menu_items'))->toBeFalse();
    expect(Schema::hasTable('roles'))->toBeFalse();
    expect(Schema::hasTable('permissions'))->toBeFalse();
    expect(Schema::hasTable('role_has_permissions'))->toBeFalse();
    expect(Schema::hasTable('model_has_permissions'))->toBeFalse();
    expect(Schema::hasTable('model_has_roles'))->toBeFalse();
    expect(Schema::hasTable('media'))->toBeTrue();
});

it('seeds database with admin user', function () {
    // Run the migrations
    $this->artisan('migrate --seed');

    // Assert that the admin user is present
    $this->assertDatabaseHas('users', [
        'email' => 'admin@example.com',
    ]);

    // Assert that the admin user has the admin role
    $this->assertDatabaseHas('model_has_roles', [
        'model_id' => 1,
        'role_id' => 1,
    ]);
});
