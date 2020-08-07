<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class BetweenWorkHours implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $start = Carbon::createFromFormat('H:i', '08:00');
        $end = Carbon::createFromFormat('H:i', '22:00');
        $time = Carbon::createFromFormat('H:i', $value);

        return $start->lte($time) && $time->lte($end);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not between 08:00 - 22:00 work intervals.';
    }
}
