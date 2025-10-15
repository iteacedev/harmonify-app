<?php

namespace App\Models;

class TokenStorage
{
    private string $file;

    public function __construct()
    {
        $this->file = BASE_DIR . '/storage/tokens.json';
    }

    public function save(array $data)
    {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function get(): ?array
    {
        if (!file_exists($this->file)) {
            return null;
        }

        return json_decode(file_get_contents($this->file), true);
    }
}
