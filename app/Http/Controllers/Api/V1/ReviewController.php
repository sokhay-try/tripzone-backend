<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\Review\ReviewResource;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends BaseController
{

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required|integer',
            'place_id' => 'required|integer',
            'rating' => 'required|integer',
            'review_text' => 'string',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try {
            $review = Review::create($input);
            return $this->sendResponse(new ReviewResource($review), 'Review created successfully.');
        }
        catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
