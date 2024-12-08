<?php

namespace App\Http\Resources\Admin\Achievement;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
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
            'date' => $this->date,
            'location' => $this->location,
            'members' => $this->members,
            'rank' => $this->rank,
            'image' => $this->image


        ];
    }
}
