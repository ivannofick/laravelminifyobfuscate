<?php

namespace Ivannofick\Laravelminifyobfuscate;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
class MinifyObfuscateProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->registerPublishables();
        $this->publishEnv();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerConfig();
    }

    protected function registerPublishables()
    {
        $this->publishes([
            __DIR__ . '/../config/laravelobfuscate.php' => config_path('laravelobfuscate.php'),
        ], 'config');
    }

    public function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravelobfuscate.php', 'laravelobfuscate.php');
    }

    protected function publishEnv()
    {
        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);
        
        if (File::exists($envPath) && strpos($envContent, 'LARAVEL_OBFUSCATE_CONDITION=') === false) {
            File::append($envPath, 'LARAVEL_OBFUSCATE_CONDITION=true' . PHP_EOL);
        }
    }
}
