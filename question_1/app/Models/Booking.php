<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'start', 'end', 'date' ];

    /**
     * Disable default timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Booking cleaner relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cleaner()
    {
        return $this->belongsTo('App\Models\Cleaner');
    }

    /**
     * Booking company relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * Get the start time.
     *
     * @param string $value
     *
     * @return \Carbon\Carbon
     */
    public function getStartAttribute($value)
    {
        return Carbon::createFromFormat('H:i', $value);
    }

    /**
     * Get the end time.
     *
     * @param string $value
     *
     * @return \Carbon\Carbon
     */
    public function getEndAttribute($value)
    {
        return Carbon::createFromFormat('H:i', $value);
    }
}
