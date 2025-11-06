<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuruResuorce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'user' => new UserResuorce($this->whenLoaded('user')),
            'nip'   => $this->nip,
            'mata_pelajaran'   => $this->mata_pelajaran,
            'alamat'   => $this->alamat,
            'foto'   => $this->foto,
        ];
    }
}
