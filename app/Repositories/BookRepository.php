<?php

namespace App\Repositories;

use App\Helpers\UploadHelper;
use App\Interfaces\BookInterface;
use App\Models\Book;
use Elastic\Elasticsearch\Client;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BookRepository implements BookInterface
{
    /** @var \Elastic\Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Get Paginated Book Data.
     *
     * @param  int  $pageNo
     * @return collections Array of Book Collection
     */
    public function getPaginatedData($perPage = 10): Paginator
    {
        return Book::orderBy('id', 'desc')
            ->paginate(intval($perPage));
    }

    /**
     * Get Searchable Book Data with Pagination.
     *
     * @param  int  $pageNo
     * @param  int  $perPage
     * @return collections Array of Book Collection
     */
    public function search($keyword, $pageNo, $perPage)
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;

        if (isset($keyword)) {

            if (config('services.search.enabled')) {
                $items = $this->searchOnElasticsearch($keyword, $pageNo, $perPage);
                $booksArr = $this->buildCollection($items);

                return new LengthAwarePaginator(
                    $booksArr,
                    $items['total']['value'],
                    $perPage,
                    Paginator::resolveCurrentPage(),
                    ['path' => Paginator::resolveCurrentPath()]
                );
            }

            return Book::where('title', 'like', '%'.$keyword.'%')
                ->orWhere('author', 'like', '%'.$keyword.'%')
                ->orWhere('genre', 'like', '%'.$keyword.'%')
                ->orWhere('isbn', 'like', '%'.$keyword.'%')
                ->paginate($perPage);
        } else {
            return Book::paginate($perPage);
        }
    }

    /**
     * Create New Book.
     *
     * @return object Book Object
     */
    public function create(array $data): Book
    {
        if (! empty($data['uploadImage'])) {
            $titleShort = Str::slug(substr($data['title'], 0, 20));
            $data['image'] = UploadHelper::upload('uploadImage', $data['uploadImage'], $titleShort.'-'.time(), 'images/books');
        }
        $data['published_at'] = date('Y-m-d', strtotime($data['published_at']));

        return Book::create($data);
    }

    /**
     * Delete Book.
     *
     * @return bool true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $book = Book::find($id);
        if (empty($book)) {
            return false;
        }

        UploadHelper::deleteFile('images/books/'.$book->image);
        $book->delete($book);

        return true;
    }

    /**
     * Get Book Detail By ID.
     *
     * @return void
     */
    public function getByID(int $id): Book|null
    {
        return Book::find($id);
    }

    /**
     * Update Book By ID.
     *
     * @return object Updated Book Object
     */
    public function update(int $id, array $data): Book|null
    {
        $book = Book::find($id);
        if (! empty($data['uploadImage'])) {
            $titleShort = Str::slug(substr($data['title'], 0, 20));
            $data['image'] = UploadHelper::update('uploadImage', $data['uploadImage'], $titleShort.'-'.time(), 'images/books', $book->image);
        } else {
            $data['image'] = $book->image;
        }

        if (is_null($book)) {
            return null;
        }
        $data['published_at'] = date('Y-m-d', strtotime($data['published_at']));

        $book->update($data);

        return $this->getByID($book->id);
    }

    private function searchOnElasticsearch(string $query, $pageNo, $perPage = 10): array
    {
        $model = new Book;
        $from = ($pageNo > 1) ? (($pageNo - 1) * $perPage) : 0;

        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => $model->getSearchType(),
            'body' => [
                'from' => $from,
                'size' => $perPage,
                'query' => [
                    'query_string' => [
                        'fields' => ['title', 'author', 'genre', 'isbn'],
                        'query' => '*'.$query.'*',
                    ],
                ],
            ],
        ]);

        return $items['hits'];
    }

    private function buildCollection(array $items)
    {
        $ids = Arr::pluck($items['hits'], '_id');

        return Book::findMany($ids)
            ->sortBy(function ($book) use ($ids) {
                return array_search($book->getKey(), $ids);
            });
    }
}
