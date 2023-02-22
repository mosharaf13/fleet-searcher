<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SearchStat extends Model
{
    use HasFactory;

    protected $casts = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'keyword',
        'ads_count',
        'links_count',
        'total_result_count',
        'raw_response',
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
}
