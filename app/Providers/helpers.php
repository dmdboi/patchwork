<?php

use Illuminate\Support\Facades\File;
use App\Models\Post;

if(!function_exists('theme_assets')) {
    /**
     * @param string|null $path
     * @return string
     */
    function theme_assets(string $path = null): string
    {
        return asset('storage/themes/' . setting('theme_name') . '/' . $path);
    }
}

if(!function_exists('theme_setting')) {
    /**
     * @param string $key
     * @return mixed
     */
    function theme_setting(string $key): mixed
    {
        if(!File::exists(base_path('Themes'))){
            return false;
        }
        if(!File::exists(base_path('Themes') .'/'.setting('theme_path')) ){
            return false;
        }
        $info = json_decode(File::get(base_path('Themes').'/'.setting('theme_path') . "/info.json"), false);
        if(isset($info->settings->{$key})){
            return $info->settings->{$key}->value;
        }

        $settingClass = new \App\Settings\ThemesSettings();

        if(isset($settingClass->{'theme_'.$key})){
            return $settingClass->{'theme_'.$key};
        }

        return false;
    }
}

if(!function_exists('load_page')){
    function load_page(string $slug,string $name=null): Post
    {
        $page = Post::query()
            ->withTrashed()
            ->where('type', 'builder')
            ->where('slug', $slug)
            ->first();

        if(!$page){
            $page = new Post();
            $page->title = $name ?: 'Empty';
            $page->type = 'builder';
            $page->slug = $slug;
            $page->is_published = true;
            $page->save();
        }
        else {
            if($page->deleted_at){
                $page->restore();
            }
        }

        return $page;
    }
}

if(!function_exists('section')){
    function section($key): ?\App\Services\Contracts\Section
    {
        $section = \App\Facades\FilamentCMS::themes()->getSections()->where('key', $key)->first();

        return $section ?? null;
    }
}

if(!function_exists('menu')){
    function menu($key){
        $menu = App\Models\Menu::where('key', $key)->first();

        if($menu){
            return collect($menu->menuItems);

        }
        else {
            return collect([]);
        }
    }
}
