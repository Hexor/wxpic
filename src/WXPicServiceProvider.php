<?php
namespace Hexor\WXPic;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class WXPicServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make(WXPicController::class);

        include(__DIR__ . '/routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');

        if(!File::exists(public_path().env('WXPIC_CACHE_DIRECTORY', '/wechat_cache_image/'))) {
            File::makeDirectory(
                public_path().env('WXPIC_CACHE_DIRECTORY', '/wechat_cache_image/'),0755,true
            );
        }

//        $this->loadViesFrom(__DIR__ . '/views', 'HelloWorld');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('wxpic', function() {
            return new WXPic();
        });

        $this->app->alias(WXPic::class, 'wxpic');
    }
}
