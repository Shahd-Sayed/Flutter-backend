<?php

namespace App\Http\Resources\Admin\About;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\Classes\FileHelpers;

class AboutResource extends JsonResource {
    /**
    * Transform the resource into an array.
    *
    * @return array<string, mixed>
    */

    public function toArray( Request $request ): array {
        return [
            'id' => $this->id,
            'name'=>$this->name,
            'description' => $this->description,
            'image' => $this->when( $this->image, fn() => FileHelpers::getFileUrl( $this->image ) ),
            'video' => $this->when(
                $this->video,
                fn() => FileHelpers::getFileUrl( $this->video ) 
            ),
        ];
    }
}
