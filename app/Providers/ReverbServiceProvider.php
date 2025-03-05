<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Broadcasting\BroadcastManager;
use Illuminate\Broadcasting\Broadcasters\NullBroadcaster;

class ReverbServiceProvider extends ServiceProvider
{
    public function boot(BroadcastManager $broadcastManager)
    {
        $broadcastManager->extend('reverb', function ($app, $config) {
            return new NullBroadcaster();
        });
    }
}