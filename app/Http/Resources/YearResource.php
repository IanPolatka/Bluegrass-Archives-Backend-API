<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class YearResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    =>  $this->when(auth('sanctum')->check() && auth('sanctum')->user()->is_admin, $this->id),
            'year'  =>  $this->year,
            'current_year'  =>  $this->current_year,
            'created_at'    =>  $this->when(auth('sanctum')->check() && auth('sanctum')->user()->is_admin, $this->created_at),
            'updated_at'    =>  $this->when(auth('sanctum')->check() && auth('sanctum')->user()->is_admin, $this->updated_at),
        ];
    }
}
