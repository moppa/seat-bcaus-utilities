<?php

namespace BCAUS\Seat\Utilities;

use BCAUS\Seat\Utilities\Observers\CharacterNotificationObserver;
use Seat\Eveapi\Models\Character\CharacterNotification;
use Seat\Services\AbstractSeatPlugin;


class BcausServiceProvider extends AbstractSeatPlugin
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
        $this->add_events();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/package.tools.sidebar.php', 'package.sidebar');
    }

    private function add_routes()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

    public function add_views()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'bcaus');
    }

    public function add_migrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');
    }

    public function add_permissions()
    {
        $this->registerPermissions(__DIR__ . '/Config/structure.permissions.php', 'bcaus-structures');
    }

    private function add_translations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'bcaus');
    }

    private function add_events()
    {
        CharacterNotification::observe(CharacterNotificationObserver::class);
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
        return 'BCAUS Utilities';
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
        return 'https://github.com/moppa/seat-bcaus-utilities';
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
        return 'bcaus-utilities';
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
        return 'bcaus';
    }
}
