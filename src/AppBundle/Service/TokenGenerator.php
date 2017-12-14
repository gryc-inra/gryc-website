<?php

namespace AppBundle\Service;

class TokenGenerator
{
    public function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
