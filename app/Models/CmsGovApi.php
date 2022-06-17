<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CmsGovApi extends Model
{
    use HasFactory;

    const ENDPOINT = 'https://data.cms.gov/provider-data/api/1/datastore/query/4pq5-n9py/0';
    const LIMIT = 500;

    public function fetchCmsProviders()
    {
        $offset = 0;
        $page = 0;

        do {
            if ($response = Http::get(self::ENDPOINT, [
                'limit' => self::LIMIT,
                'offset' => $offset,
                'count' => 'true',
                'results' => 'true',
                'schema' => 'true',
                'keys' => 'true',
                'format' => 'json',
                'rowIds' => 'true',
            ])) {
                $parsed = json_decode($response, true);

                foreach ($parsed['results'] as $i => $result) {
                    CmsProvider::create([
                        'federal_provider_number' => $result['federal_provider_number'],
                        'provider_name' => $result['provider_name'],
                        'number_of_certified_beds' => $result['number_of_certified_beds'],
                    ]);
                }

                if (count($parsed['results']) > 0) {
                    $offset += self::LIMIT;
                    $page++;
                    Log::info("Page: {$page} | OFFSET: {$offset}");
                } else {
                    unset($page);
                }
            } else {
                unset($page);
            }
        } while (isset($page));
    }
}
