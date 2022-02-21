<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/category",
     *     description="Get List of categories",
     *     tags={"Category"},
     *     @OA\Response(response="200", description="Created", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=500,description="Internal server error"),
     * )
     */
    public function index()
    {
        try {
            $categories = Category::paginate(10);
            $resource = new CategoryCollection($categories);
            return $resource->response()->setStatusCode(Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


   /**
     * @OA\Post(
     *     path="/category",
     *     description="Create category",
     *     tags={"Category"},
     *     @OA\Parameter(
     *          name="name",
     *          description="Category Name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="parent_id",
     *          description="ID of parent category ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="Success", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=500,description="Internal server error"),
     *     @OA\Response(response=422,description="Unprocessable Entity - validation failed"),
     * )
     */
    public function store(CategoryCreateRequest $request)
    {
        try {
            $inputs = $request->validated();
            $category = Category::create($inputs);
            return response()->json($category, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


   /**
     * @OA\Put(
     *     path="/category/{id}",
     *     description="Update category",
     *     tags={"Category"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Category id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="name",
     *          description="Category Name",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="parent_id",
     *          description="ID of parent category ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="Success", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=500,description="Internal server error"),
     *     @OA\Response(response=422,description="Unprocessable Entity - validation failed"),
     * )
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $inputs = $request->validated();
            $category = Category::findOrFail($id);
            $category = $category->update($inputs);
            return response()->json($category, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/category/{id}",
     *     description="Delete category",
     *     tags={"Category"},
     *      @OA\Parameter(
     *          name="id",
     *          description="Category id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="Deleted", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=500,description="Internal server error"),
     * )
     */
    public function destroy($id)
    {
        try {
            $status = Category::findOrFail($id)->delete();
            return response()->json($status, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
