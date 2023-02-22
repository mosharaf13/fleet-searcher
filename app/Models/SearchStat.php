<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SearchStat extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i a',
        'updated_at' => 'datetime:Y-m-d h:i a',
    ];

    protected $fillable = [
        'keyword',
        'ads_count',
        'links_count',
        'total_result_count',
        'raw_response',
    ];

    const MASS_RETURN_ATTRIBUTES = [
        'id',
        'keyword',
        'ads_count',
        'links_count',
        'total_result_count',
        'created_at'
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
}
