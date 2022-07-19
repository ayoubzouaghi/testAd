<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class ActiveDirectoryParameterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'hosts'    => Crypt::decryptString($this->hosts),
            'port'     => Crypt::decryptString($this->port),
            'username' => Crypt::decryptString($this->username),
            'password' => Crypt::decryptString($this->password),
            'dc'       => Crypt::decryptString($this->dc),
        ];
    }
}
