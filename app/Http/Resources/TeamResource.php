<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'    =>  $this->id,
            'name'  =>  $this->name,
            'mascot'    =>  $this->mascot,
            'slug'  =>  $this->slug,
            'city'    =>  $this->city,
            'state' =>  $this->state,
            'logo'  =>  asset('images/team-logos/' . $this->logo),
            'creator'    =>  $this->when(auth('sanctum')->check() && auth('sanctum')->user()->is_admin, $this->whenLoaded('creator', fn () => UserResource::make($this->creator))),
            'is_active'    =>  $this->when(auth('sanctum')->check() && auth('sanctum')->user()->is_admin, $this->is_active),
            'created_at'    =>  $this->when(auth('sanctum')->check() && auth('sanctum')->user()->is_admin, $this->created_at),
            'updated_at'    =>  $this->when(auth('sanctum')->check() && auth('sanctum')->user()->is_admin, $this->updated_at),
        ];  
    }
}
