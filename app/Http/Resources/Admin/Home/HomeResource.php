<?php

namespace App\Http\Resources\Admin\Home;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\Classes\FileHelpers;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'image' => $this->when( $this->image, fn() => FileHelpers::getFileUrl( $this->image ) ),
        ];
    }
}
