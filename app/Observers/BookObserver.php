<?php

namespace App\Observers;

use App\Models\Book;
use Elastic\Elasticsearch\Client;

class BookObserver
{
    /** @var \Elastic\Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Handle the Book "deleted" event.
     *
     * @return void
     */
    public function deleted(Book $book)
    {
        if (config('services.search.enabled')) {
            $this->elasticsearch->delete([
                'index' => $book->getSearchIndex(),
                'type' => $book->getSearchType(),
                'id' => $book->getKey(),
            ]);
        }
    }

    /**
     * Handle the Book "saved" event.
     *
     * @return void
     */
    public function saved(Book $book)
    {
        if (config('services.search.enabled')) {
            $this->elasticsearch->index([
                'index' => $book->getSearchIndex(),
                'type' => $book->getSearchType(),
                'id' => $book->getKey(),
                'body' => $book->toSearchArray(),
            ]);
        }
    }
}
