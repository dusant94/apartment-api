<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApartmentCreateRequest;
use App\Http\Requests\ApartmentUpdateRequest;
use App\Http\Resources\ApartmentCollection;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $apartments = Apartment::SortAndOrderBy($request)->paginate(10);
        $resource = new ApartmentCollection($apartments);
        return $resource->response()->setStatusCode(Response::HTTP_OK);;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ApartmentCreateRequest $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ApartmentUpdateRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
