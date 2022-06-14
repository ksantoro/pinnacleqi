<?php

namespace App\Imports;

use App\Models\CmsProvider;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class CmsProvidersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return Model|null
    */
    public function model(array $row)
    {
        return new CmsProvider([
            'number_of_certified_beds' => $row['number_of_certified_beds'],
            'provider_name' => $row['provider_name'],
            'federal_provider_number' => $row['federal_provider_number'],
        ]);
    }
}
