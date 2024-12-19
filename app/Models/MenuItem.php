<?php

namespace App\Models;

use Filament\Resources\Concerns\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    use Translatable;

    public array $translatable = [
        'title'
    ];

    protected $casts = [
        'title' => 'array',
        'badge' => 'array',
        'permissions' => 'array',
        'is_route' => 'boolean',
        'new_tab' => 'boolean',
    ];

    /**
     * The table name
     *
     * @var string
     */
    protected $table = "menu_items";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "menu_id",
        "title",
        "group",
        "icon",
        "is_route",
        "route",
        "url",
        "new_tab",
        "permissions",
        "order",
    ];


    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

}
