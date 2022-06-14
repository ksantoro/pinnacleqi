<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use phpDocumentor\Reflection\Types\Self_;

class CmsGovApi extends Model
{
    use HasFactory;

    const ENDPOINT = 'https://data.cms.gov/provider-data/api/1/datastore/query/4pq5-n9py';
    const LIMIT = 500;

    public function fetchCmsProviders()
    {
        $offset = 0;
        $page = 0;

        do {
            if ($response = Http::withHeaders([
                'Accept' => 'application/json'
            ])->get(self::ENDPOINT, [
                'limit' => self::LIMIT,
                'offset' => $offset,
                'count' => true,
                'results' => true,
                'schema' => true,
                'keys' => true,
                'format' => 'json',
                'rowIds' => true,
            ])) {
                $parsed = json_decode($response, true);
                foreach ($parsed['results'] as $i => $result) {
                    CmsProvider::create([
                        'federal_provider_number' => $result['federal_provider_number'],
                        'provider_name' => $response['provider_name'],
                        'number_of_certified_beds' => $response['number_of_certified_beds'],
                    ]);
                }

                $offset += self::LIMIT;
                $page++;
            } else {
                unset($page);
            }
        } while (isset($page));
    }
}
