<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cleaner extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name' ];

    /**
     * Disable default timestamps
     *
     * @var boolean
     */
    public $timestamps = false;


    /**
     * Cleaner booking relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany('App\Models\Booking');
    }
}
