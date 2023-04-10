<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResponseTrait;

class UserController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * @OA\GET(
     *     path="/api/user",
     *     tags={"Authentication"},
     *     summary="Authenticated User Profile",
     *     description="Authenticated User Profile",
     *     @OA\Response(response=200, description="Authenticated User Profile" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function __invoke(Request $request)
    {
        try {
            if(auth("sanctum")->check()) {
                $data = auth("sanctum")->user();
            
                return $this->responseSuccess($data, 'Profile Fetched Successfully !');
            } else {
                $this->responseError(null, 'Not Authenticated', Response::HTTP_UNAUTHORIZED);
            }
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        if(auth("sanctum")->check()){
            return response()->json(auth("sanctum")->user());
        }
    }
}
