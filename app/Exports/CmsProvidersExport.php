<?php

namespace App\Exports;

use App\Models\CmsProvider;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class CmsProvidersExport implements FromCollection
{
    /**
    * @return Collection
    */
    public function collection()
    {
        return CmsProvider::all();
    }
}
