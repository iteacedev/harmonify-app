<?php

namespace App\Models;

class LastToot
{
    private string $file;

    public function __construct()
    {
        $this->file = BASE_DIR . '/last_toot.json';
    }

    public function save(array $data)
    {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function get(): ?string
    {
        if (!file_exists($this->file)) {
            return null;
        }

        $data = json_decode(file_get_contents($this->file), true);
        return $data['status'] ?? null;
    }
}
