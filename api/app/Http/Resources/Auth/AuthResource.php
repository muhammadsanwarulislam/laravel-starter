<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier'        => $this['identifier'],
            'identifier_type'   => $this['identifier_type'],
            'expires_at'        => $this['expires_at'],
            'token_type'        => $this['token_type'],
            'token'             => $this['temporary_token']
        ];
    }
}
