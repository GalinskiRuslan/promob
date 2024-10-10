<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GoogleDriveServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }
    public function boot()
    {
        $client = new \Google_Client;
    }
}
