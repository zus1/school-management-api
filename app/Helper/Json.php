<?php

namespace App\Helper;

class Json
{
    public function isJson(?string $payload): bool
    {
        if(is_numeric($payload) || is_null($payload)) {
            return false;
        }

        try{
            json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return false;
        }

        return true;
    }
}
