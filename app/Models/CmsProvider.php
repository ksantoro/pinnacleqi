<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsProvider extends Model
{
    use HasFactory;

    public $fillable = [
        'federal_provider_number',
        'provider_name',
        'number_of_certified_beds',
    ];
}
