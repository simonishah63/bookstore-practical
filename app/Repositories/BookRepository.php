<?php

namespace App\Repositories;

use Illuminate\Support\Str;
use App\Helpers\UploadHelper;
use App\Interfaces\BookInterface;
use App\Models\Book;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Elastic\Elasticsearch\ClientBuilder;

class BookRepository implements BookInterface
{
    /**
     * Serach client
     *
     * @var bookRepository
     */
    public $client;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->client = ClientBuilder::create()->setHosts(["http://127.0.0.1:9300"])->build();
    }

    /**
     * Get Paginated Book Data.
     *
     * @param int $pageNo
     * @return collections Array of Book Collection
     */
    public function getPaginatedData($perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;
        return Book::orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get Searchable Book Data with Pagination.
     *
     * @param int $pageNo
     * @return collections Array of Book Collection
     */
    public function searchBook($keyword, $perPage): Paginator
    {
        $perPage = isset($perPage) ? intval($perPage) : 10;

        if(isset($keyword)) {
            return Book::where('title', 'like', '%' . $keyword . '%')
            ->orWhere('author', 'like', '%' . $keyword . '%')
            ->orWhere('genre', 'like', '%' . $keyword . '%')
            ->orWhere('isbn', 'like', '%' . $keyword . '%')
            ->paginate($perPage);
            // $params = [
            //     'index' => 'bookstore',
            //     'body'  => [
            //         'query' => [
            //             'match' => [
            //                 'title' => $keyword,
            //             ]
            //         ]
            //     ]
            // ];
            // $response = $this->client->search($params);
            // return $response['hits']['hits'];
        } else {
            return Book::paginate($perPage);
        }
    }

    /**
     * Create New Book.
     *
     * @param array $data
     * @return object Book Object
     */
    public function create(array $data): Book
    {
        // if (!empty($data['image'])) {
        //     $titleShort      = Str::slug(substr($data['title'], 0, 20));
        //     $data['image'] = UploadHelper::upload('image', $data['image'], $titleShort . '-' . time(), 'images/books');
        // }
        $data['published_at'] = date('Y-m-d', strtotime($data['published_at']));
            
        return Book::create($data);
    }

    /**
     * Delete Book.
     *
     * @param int $id
     * @return boolean true if deleted otherwise false
     */
    public function delete(int $id): bool
    {
        $book = Book::find($id);
        if (empty($book)) {
            return false;
        }

        //UploadHelper::deleteFile('images/books/' . $book->image);
        $book->delete($book);
        return true;
    }

    /**
     * Get Book Detail By ID.
     *
     * @param int $id
     * @return void
     */
    public function getByID(int $id): Book|null
    {
        return Book::find($id);
    }

    /**
     * Update Book By ID.
     *
     * @param int $id
     * @param array $data
     * @return object Updated Book Object
     */
    public function update(int $id, array $data): Book|null
    {
        $book = Book::find($id);
        // if (!empty($data['image'])) {
        //     $titleShort = Str::slug(substr($data['title'], 0, 20));
        //     $data['image'] = UploadHelper::update('image', $data['image'], $titleShort . '-' . time(), 'images/books', $book->image);
        // } else {
        //     $data['image'] = $book->image;
        // }

        if (is_null($book)) {
            return null;
        }
        $data['published_at'] = date('Y-m-d', strtotime($data['published_at']));
        
        $book->update($data);

        return $this->getByID($book->id);
    }
}