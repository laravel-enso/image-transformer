<?php

namespace LaravelEnso\ImageTransformer;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageServiceProvider;

class ImageTransformerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->register(ImageServiceProvider::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('Image', Image::class);
    }
}
