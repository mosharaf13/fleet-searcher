<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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
        'user_id'
    ];

    const MASS_RETURN_ATTRIBUTES = [
        'id',
        'keyword',
        'ads_count',
        'links_count',
        'total_result_count',
        'created_at'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('age', function (Builder $builder) {
            $builder->where('user_id', Auth::id());
        });
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
