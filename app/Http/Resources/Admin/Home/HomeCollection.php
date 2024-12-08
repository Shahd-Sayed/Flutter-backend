<?php

namespace App\Http\Resources\Admin\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Admin\Home\HomeResource;

class HomeCollection extends ResourceCollection {
    /**
    * Transform the resource collection into an array.
    *
    * @return array<int|string, mixed>
    */

    public function toArray( Request $request ): array {
        return [
            'data' => HomeResource::collection( $this->collection ),
            'meta' => [
                'pagination' => [
                    'total' => $this->total(),
                    'count' => $this->count(),
                    'per_page' => $this->perPage(),
                    'current_page' => $this->currentPage(),
                    'total_pages' => $this->lastPage(),
                ],
            ],
        ];
    }
}
