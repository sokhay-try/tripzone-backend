<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Place;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Place\PlaceResource;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\CustomPaginator;

class PlaceController extends BaseController
{

    public function index(Request $request) {
        $perPage = $request->query('per_page');
        $place = new CustomPaginator(Place::with('province')->paginate($perPage));
        return $this->sendResponse($place, 'Place retrieved successfully!');
    }

    public function show(Place $place)
    {
        $place->province;
        return $this->sendResponse(new PlaceResource($place), 'Place detail retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['name'] = strtolower($request->name);

        $validator = Validator::make($input, [
            'name' => 'required|unique:places,name|max:100|string',
            'description' => 'required|string',
            'province_id' => 'required|integer',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $place = Place::create([
            'name' => $input['name'],
            'description' => $input['description'],
            'province_id' => $input['province_id'],
            'created_by' => $request->user()->id,
        ]);

        return $this->sendResponse(new PlaceResource($place), 'Place created successfully.');
    }

    public function update(Request $request, Place $place)
    {
        $input = $request->all();
        $input['name'] = strtolower($request->name);

        if ($input['name'] != $place->name) {
            $validator = Validator::make($input, [
                'name' => 'required|unique:places,name|max:100|string',
                'description' => 'required|string',
                'province_id' => 'required|integer',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }
        }

        $place->name = $input['name'];
        $place->description = $input['description'];
        $place->province_id = $input['province_id'];
        $place->created_by  = $request->user()->id;
        $place->save();

        return $this->sendResponse(new PlaceResource($place), 'Place updated successfully.');
    }

    public function destroy(Place $place)
    {
        $place->delete();
        return $this->sendResponse([], 'Place deleted successfully.');
    }

    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'place_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $place = Place::findOrFail($request->place_id);
        $place->status = $request->status;
        $place->save();

        return $this->sendResponse(new PlaceResource($place), 'Place status updated successfully.');
    }

    public function updateVisitor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'place_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $place = Place::findOrFail($request->place_id);
        $place->visitor += 1;
        $place->save();

        return $this->sendResponse(new PlaceResource($place), 'Place visitor updated successfully.');
    }

}
