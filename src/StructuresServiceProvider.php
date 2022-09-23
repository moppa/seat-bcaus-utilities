<?php

namespace BCAUS\Seat\Structures;

use Seat\Services\AbstractSeatPlugin;

class StructuresServiceProvider extends AbstractSeatPlugin
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->add_routes();
        $this->add_views();
        $this->add_translations();
        $this->add_permissions();
        $this->add_migrations();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $toolsEntries = config('package.sidebar.tools.entries');
        array_push($toolsEntries, [
            'permission'    => 'bcaus-structures.structures_view',
            'name'          => 'Structure Management',
            'icon'          => 'fas fa-hospital',
            'route'         => 'tools.bcaus-structures.list',
        ]);
        config([
            'package.sidebar.tools.entries' => array_unique($toolsEntries, SORT_REGULAR),
        ]);
    }

    private function add_routes()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

    public function add_views()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'bcaus-structures');
    }

    public function add_migrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }

    public function add_permissions()
    {
        $this->registerPermissions(__DIR__ . '/Config/package.permissions.php', 'bcaus-structures');
    }

    private function add_translations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'bcaus-structures');
    }

    /**
     * Return the plugin public name as it should be displayed into settings.
     *
     * @example SeAT Web
     *
     * @return string
     */
    public function getName(): string
    {
        return 'BCAUS Structure Management';
    }

    /**
     * Return the plugin repository address.
     *
     * @example https://github.com/eveseat/web
     *
     * @return string
     */
    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/';
    }

    /**
     * Return the plugin technical name as published on package manager.
     *
     * @example web
     *
     * @return string
     */
    public function getPackagistPackageName(): string
    {
        return 'seat-bcaus-structures';
    }

    /**
     * Return the plugin vendor tag as published on package manager.
     *
     * @example eveseat
     *
     * @return string
     */
    public function getPackagistVendorName(): string
    {
        return 'moppa';
    }
}
