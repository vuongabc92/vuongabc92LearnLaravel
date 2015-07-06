<?php

namespace King\Frontend;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class FrontendServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

        //Load helpers
        Include_once realpath(__DIR__ . '/support/helpers.php');

        //Load views
        $this->loadViewsFrom(realpath(__DIR__ . '/../resources/views'), 'frontend');

        //Load translation
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'frontend');

        //Set up routes
        $this->setupRoutes($this->app->router);

        /** Merge config */
        $this->mergeConfigFrom(
            __DIR__ . '/config/frontend.php', 'front'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/config/constants.php', 'constant'
        );

        //Publish assets
        $this->publishes([
            __DIR__ . '/../public' => public_path('packages/king/frontend'),
        ], 'public');
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function setupRoutes(Router $router) {
        $router->group(['namespace' => 'King\Frontend\Http\Controllers'], function($router) {
            require __DIR__ . '/Http/routes.php';
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->registerFrontend();
        config([
            'config/frontend.php',
        ]);
    }

    private function registerFrontend() {
        $this->app->bind('frontend', function($app) {
            return new Frontend($app);
        });
    }

}
