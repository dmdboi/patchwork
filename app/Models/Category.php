<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property int $parent_id
 * @property string $for
 * @property string $type
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property string $icon
 * @property string $color
 * @property bool $is_active
 * @property bool $show_in_menu
 * @property string $created_at
 * @property string $updated_at
 * @property Categorable[] $categorables
 * @property Category $category
 * @property CategoriesMeta[] $categoriesMetas
 * @property Content[] $contents
 */
class Category extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $translatable = [
        'name',
        'description',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'for',
        'parent_id',
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'is_active',
        'show_in_menu',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_menu' => 'boolean',
    ];

    public function children()
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categoriesMetas()
    {
        return $this->hasMany('App\Models\CategoriesMeta');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'posts_has_category', 'category_id', 'post_id');
    }
}
