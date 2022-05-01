<?php

namespace Avxman\Sitemap\Providers;

use Avxman\Sitemap\Classes\SitemapClass;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @param Filesystem $filesystem
     * @return void info
     */
    public function boot(Filesystem $filesystem){
        if(App()->runningInConsole()){
            $this->publishes($this->getFilesNameAll($filesystem , 0, true), 'avxman-sitemap-all');
            $this->publishes($this->getFilesNameAll($filesystem), 'avxman-sitemap-migrate');
            $this->publishes($this->getFilesNameAll($filesystem, 1), 'avxman-sitemap-model');
            $this->publishes($this->getFilesNameAll($filesystem, 2), 'avxman-sitemap-config');
        }
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(self::class, SitemapClass::class);
    }

    /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @param Filesystem $filesystem
     * @param string $name
     * @return string
     */
    protected function getMigrationFileName(Filesystem $filesystem, string $name = ''): string
    {
        $timestamp = date('Y_m_d_His');

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $name) {
                return $filesystem->glob($path.'*_'.$name.'.php');
            })->push($this->app->databasePath().DIRECTORY_SEPARATOR."migrations/{$timestamp}_{$name}.php")
            ->first();
    }

    /**
     * Returns existing model file if found, else uses the current.
     *
     * @param Filesystem $filesystem
     * @param string $name
     * @return string
     */
    protected function getModelFileName(Filesystem $filesystem, string $name = ''): string
    {
        return Collection::make(app_path('Models').DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $name) {
                return $filesystem->glob($path.$name.'.php');
            })->push(app_path('Models'.DIRECTORY_SEPARATOR."{$name}.php"))
            ->first();
    }

    /**
     * Create specified files in folders
     * @param Filesystem $filesystem
     * @param int $index
     * @param bool $all
     * @return array
     */
    protected function getFilesNameAll(Filesystem $filesystem, int $index = 0, bool $all = false) : array{
        $collect = collect()->push(
            [
                dirname(__DIR__, 2).'/database/migrations/create_sitemap_table.php.stub' => $this->getMigrationFileName($filesystem, 'create_sitemap_table'),
            ],
            [
                dirname(__DIR__, 2).'/Models/SitemapModel.php.stub' => $this->getModelFileName($filesystem, 'SitemapModel'),
            ],
            [
                dirname(__DIR__, 2).'/config/' => base_path('config').DIRECTORY_SEPARATOR,
            ]
        );
        return $all
            ? collect()->merge($collect->get(0))->merge($collect->get(1))->merge($collect->get(2))->toArray()
            : $collect->get($index);
    }


}
