<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use App\Models\Book;
use App\Observers\BookObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindSearchClient();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Book::observe(BookObserver::class);
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts(config('services.search.hosts'))
                ->build();
        });
    }
}
