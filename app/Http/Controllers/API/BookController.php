<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Repositories\BookRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Info(
 *     description="API Documentation - Bookstore",
 *     version="1.0.0",
 *     title="Bookstore API Documentation",
 *
 *     @OA\Contact(
 *         email="simonishah63@gmail.com"
 *     ),
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API Server"
 * )
 *
 * @OA\Tag(
 *     name="Bookstore Api",
 *     description="API Endpoints of bookstore Api"
 * )
 */
class BookController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Book Repository class.
     *
     * @var bookRepository
     */
    public $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->middleware('auth:sanctum');
        $this->bookRepository = $bookRepository;
    }

    /**
     * @OA\GET(
     *     path="/api/books",
     *     tags={"Books"},
     *     summary="All Books - Publicly Accessible",
     *     description="All Books - Publicly Accessible",
     *     operationId="search",
     *
     *     @OA\Parameter(name="perPage", description="perPage, eg; 20", example=20, in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="search", description="search, eg; Test", example="Test", in="query", @OA\Schema(type="string")),
     *
     *     @OA\Response(response=200, description="All Books - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index(Request $request)
    {
        try {
            $data = $this->bookRepository->search($request->search, $request->page, $request->perPage);

            return $this->responseSuccess($data, 'Books Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api/books/add",
     *     tags={"Books"},
     *     summary="Create New Book",
     *     description="Create New Book",
     *     operationId="store",
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="title", type="string", example="Books 1"),
     *              @OA\Property(property="author", type="string", example="Books Author"),
     *              @OA\Property(property="gener", type="string", example="Books Genre"),
     *              @OA\Property(property="description", type="string", example="Description"),
     *              @OA\Property(property="isbn", type="number", example="12345"),
     *              @OA\Property(property="publisher", type="string", example="Books Publisher"),
     *              @OA\Property(property="published_at", type="date", example="2023-04-01"),
     *              @OA\Property(property="uploadImage", type="string", format="binary", example=""),
     *          ),
     *      ),
     *
     *      @OA\Response(response=200, description="Create New Book" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store(BookRequest $request)
    {
        try {
            $book = $this->bookRepository->create($request->all());
            $book->published_at = date('Y-m-d', strtotime($book->published_at));

            return $this->responseSuccess($book, 'New Book Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/books/show/{id}",
     *     tags={"Books"},
     *     summary="Show Book Details",
     *     description="Show Book Details",
     *     operationId="show",
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Show Book Details"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show($id)
    {
        try {
            $data = $this->bookRepository->getByID($id);
            if (is_null($data)) {
                return $this->responseError(null, 'Book Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Book Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\PUT(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Update Book",
     *     description="Update Book",
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\RequestBody(
     *
     *          @OA\JsonContent(
     *              type="object",
     *
     *              @OA\Property(property="title", type="string", example="Books 1"),
     *              @OA\Property(property="author", type="string", example="Books Author"),
     *              @OA\Property(property="gener", type="string", example="Books Genre"),
     *              @OA\Property(property="description", type="string", example="Description"),
     *              @OA\Property(property="isbn", type="number", example="12345"),
     *              @OA\Property(property="publisher", type="string", example="Books Publisher"),
     *              @OA\Property(property="published_at", type="date", example="2023-04-01"),
     *              @OA\Property(property="uploadImage", type="string", format="binary", example=""),
     *          ),
     *      ),
     *     operationId="update",
     *
     *     @OA\Response(response=200, description="Update Book"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update(BookRequest $request, $id)
    {
        try {
            $data = $this->bookRepository->update($id, $request->all());
            if (is_null($data)) {
                return $this->responseError(null, 'Book Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Book Updated Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/api/books/{id}",
     *     tags={"Books"},
     *     summary="Delete Book",
     *     description="Delete Book",
     *     operationId="destroy",
     *
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *
     *     @OA\Response(response=200, description="Delete Book"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function destroy($id)
    {
        try {
            $book = $this->bookRepository->getByID($id);
            if (empty($book)) {
                return $this->responseError(null, 'Book Not Found', Response::HTTP_NOT_FOUND);
            }

            $deleted = $this->bookRepository->delete($id);
            if (! $deleted) {
                return $this->responseError(null, 'Failed to delete the book.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->responseSuccess($book, 'Book Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
