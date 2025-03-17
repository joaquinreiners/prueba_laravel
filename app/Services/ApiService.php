<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ApiService
{
    public function fetchEntries()
    {
        try {

            $response = Http::get('https://api.publicapis.org/entries');
                return $response->json()['entries'];

        } catch (\Exception $e) {

            $file = Storage::get('entries.json');
            return json_decode($file, true)['entries'] ?? [];
        }
    }
}
