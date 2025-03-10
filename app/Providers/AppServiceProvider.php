<?php

namespace App\Providers;

use App\Models\Facture;
use App\Models\Ticket;
use App\Observers\FactureObserver;
use App\Observers\TicketObserver;
use App\Repositories\ClientRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ClientRepository::class,
            function ($app) {
                return new ClientRepository(new Client());
            }
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));


        // Register the observer
       Ticket::observe(TicketObserver::class);


    }
}
