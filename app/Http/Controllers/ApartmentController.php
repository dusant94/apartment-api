<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApartmentCreateRequest;
use App\Http\Requests\ApartmentUpdateRequest;
use App\Http\Resources\ApartmentCollection;
use App\Models\Apartment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class ApartmentController extends Controller
{

    /**
     * @OA\Get(
     *     path="/apartment",
     *     description="Get List of apartments",
     *     tags={"Apartments"},
     *     @OA\Parameter(
     *          name="sort",
     *          description="Sorting parameters",
     *          required=false,
     *          in="query",
     *          example="name:asc,price:desc,created_at:desc",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="name",
     *          description="Filter Apartment Name",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="price",
     *          description="Filter Apartment price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="currency",
     *          description="Filter Currency of price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="size",
     *          description="Filter by size",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="numeric"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="balcony_size",
     *          description="Filter by balcony_size",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="numeric"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="location",
     *          description="Filter by location",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          description="Filter by Description",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="text"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="category_id",
     *          description="Filter by ID category_id ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="Created", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=500,description="Internal server error"),
     * )
     */
    public function index(Request $request)
    {
        try {
            $apartments = Apartment::SortAndOrderBy($request)->FilterBy($request)->paginate(10);
            $resource = new ApartmentCollection($apartments);
            return $resource->response()->setStatusCode(Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/apartment",
     *     description="Get List of apartments",
     *     tags={"Apartments"},
     *     @OA\Parameter(
     *          name="name",
     *          description="Apartment Name",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="price",
     *          description="Apartment price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="currency",
     *          description="Currency of price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="properties",
     *          description="Apartment Properties",
     *          required=false,
     *          in="query",
     *          example="{'size':'numeric','balcony_size':'numeric','location':'string'}",
     *          @OA\Schema(
     *              type="json"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          description="Apartment Description",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="text"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="category_id",
     *          description="ID of category to which  apartment belongs",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="201", description="Created", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=500,description="Internal server error"),
     *     @OA\Response(response=422,description="Unprocessable Entity - validation failed"),
     * )
     */
    public function store(ApartmentCreateRequest $request)
    {
        try {
            $inputs = $request->validated();
            $apartment = Apartment::create($inputs);
            return response()->json($apartment, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Put(
     *     path="/apartment/{id}",
     *     description="Get List of apartments",
     *     tags={"Apartments"},
     *     @OA\Parameter(
     *          name="id",
     *          description="Apartment id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="name",
     *          description="Apartment Name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="price",
     *          description="Apartment price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="currency",
     *          description="Currency of price",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="properties",
     *          description="Apartment Properties",
     *          required=false,
     *          in="query",
     *          example="{'size':'numeric','balcony_size':'numeric','location':'string'}",
     *          @OA\Schema(
     *              type="json"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          description="Apartment Description",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="text"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="category_id",
     *          description="ID of category to which  apartment belongs ",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="200", description="Created", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=500,description="Internal server error"),
     *     @OA\Response(response=422,description="Unprocessable Entity - validation failed"),
     * )
     */
    public function update(ApartmentUpdateRequest $request, $id)
    {
        try {
            $inputs = $request->validated();
            $apartment = Apartment::findOrFail($id);
            $apartment = $apartment->update($inputs);
            return response()->json($apartment, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/apartment/{id}",
     *     description="Delete apartment",
     *     tags={"Apartments"},
     *      @OA\Parameter(
     *          name="id",
     *          description="Apartment id",
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
            $apartment = Apartment::findOrFail($id)->delete();
            return response()->json($apartment, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
