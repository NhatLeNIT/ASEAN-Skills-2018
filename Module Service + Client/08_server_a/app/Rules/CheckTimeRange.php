<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class CheckTimeRange implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $time = Carbon::createFromTimeString($value);
        $timeStart = Carbon::createFromTimeString('08:30:00');
        $timeEnd = Carbon::createFromTimeString('18:00:00');
        return $time->between($timeStart, $timeEnd);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation time range message.';
    }
}
