<?php

namespace App\Console\Commands;

use \App\Models\Book;
use Illuminate\Console\Command;
use Elasticsearch\ClientBuilder;
use Exception;


class IndexBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:books';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index all books for elasticsearch';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        
        $client = ClientBuilder::create()->setHosts(["http://127.0.0.1:9300"])->build();
        
        // $response = $client->indices()->delete([
        //     'index' => 'bookstore'
        // ]);

        // Book::createIndex($shards = null, $replicas = null);
        // Book::putMapping($ignoreConflicts = true);
        //Book::getMapping();
        //Book::addAllToIndex();

        $indexParams = [
            'index' => 'bookstore',
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 1
                ],
                'mappings' => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
                        'title' => [
                            'type' => 'keyword'
                        ],
                        'author' => [
                            'type' => 'keyword'
                        ],
                        'genre' => [
                            'type' => 'keyword'
                        ],
                        'isbn' => [
                            'type' => 'integer'
                        ]
                    ]
                ]
            ]
        ];
        
        $response = $client->indices()->create($indexParams);

        $books = Book::all();

        foreach ($books as $book) {
            try {
                $response = $client->index([
                    'index' => 'bookstore',
                    'id' => $book->id,
                    'body' => [
                        'title' => $book->title,
                        'author' => $book->author,
                        'genre' => $book->genre,
                        'isbn' => $book->isbn,
                    ]
                ]);
            } catch (Exception $e) {
                $this->info($e->getMessage());
            }
        }

        $this->info("Books were successfully indexed");

        return;
    }
}
