<?php
namespace Themes\BC;

use Database\Seeders\DatabaseSeeder;

class ThemeProvider extends \Themes\Base\ThemeProvider
{

    public static $version = '1.0';
    public static $name = 'Travels2020';
    public static $seeder = DatabaseSeeder::class;

    public function register()
    {
        parent::register();
        $this->app->register(\Themes\BC\Core\ModuleProvider::class);
    }
}
