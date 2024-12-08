<?php

namespace App\Http\Resources\Admin\Committee;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommitteeResource extends JsonResource {
    /**
    * Transform the resource into an array.
    *
    * @return array<string, mixed>
    */

    public function toArray( Request $request ): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' =>$this->image,
            'courses' => $this->courses,
            'aboutUs' => $this->aboutUs,
        ];
    }
}
