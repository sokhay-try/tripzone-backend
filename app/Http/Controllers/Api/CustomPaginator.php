<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomPaginator extends ResourceCollection
{
        /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'pagination' => [
                "current_page" => $this->currentPage(),
                "per_page" =>  $this->perPage(),
                "total" =>  $this->total(),
            ],
        ];
    }

}
