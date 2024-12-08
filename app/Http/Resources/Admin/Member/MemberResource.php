<?php

namespace App\Http\Resources\Admin\Member;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\Classes\FileHelpers;
class MemberResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'role' => $this->role,
            'link1' => $this->link1,
            'link2' => $this->link2,
            'link3' => $this->link3,
            'image' => $this->when( $this->image, fn() => FileHelpers::getFileUrl( $this->image ) ),
        ];
    }
}
