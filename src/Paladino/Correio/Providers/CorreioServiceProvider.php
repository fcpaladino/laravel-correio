<?php
namespace Paladino\Correio\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Paladino\Correio\Correio;


class CorreioServiceProvider extends BaseServiceProvider
{

    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->bind('correio', function(){
            return new Correio;
        });
    }
}
