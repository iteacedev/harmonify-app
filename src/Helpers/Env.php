<?php

namespace App\Helpers;

class Env
{
    public static function get(string $value)
    {
        return $_ENV[$value];
    }
}
