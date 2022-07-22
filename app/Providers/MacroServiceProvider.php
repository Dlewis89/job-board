<?php

namespace App\Providers;

use App\Macros\ResponseMacro;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Response::mixin(new ResponseMacro());
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
