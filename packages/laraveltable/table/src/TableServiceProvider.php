<?php

namespace Laraveltable\Table;

use Illuminate\Support\ServiceProvider;

class TableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/assets' => public_path('vendor/laraveltable'), // <script src="{{ asset('/vendor/laraveltable/js/laraveltable-sortable.js')}}"></script>
        ], 'public');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/routes.php';
        $this->app->make('Laraveltable\Table\TableController');
    }
}
