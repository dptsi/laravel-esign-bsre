<?php
namespace Dptsi\EsignBsre\Providers;

use Dptsi\EsignBsre\Core\EsignBsreManager;
use Illuminate\Support\ServiceProvider;

class BsreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publish();
    }

    public function register()
    {
        $this->app->singleton(
            'esign_bsre',
            function () {
                $storage = new EsignBsreManager();

                return $storage;
            }
        );
    }

    protected function publish()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/bsre.php' => config_path('bsre.php'),
            ],
            'dptsi-esign-bsre'
        );
    }
}