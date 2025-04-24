<?php

namespace App\Http\Requests\Workspace\Calendar;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "week_start" => ["required", "in:sunday,monday,tuesday,wednesday,thursday,friday,saturday"],
            "show_weekdays" => ["required", "boolean"],
            "timeslot_size" => ["required", "in:small,medium,large,extra_large"],
            "time_increment" => ["required", "in:5_minute,10_minute,15_minute,20_minute,30_minute,60_minute"],
            "time_format" => ["required", "in:12_hr,24_hr"]
        ];
    }


    public function messages(){
        return [
            "week_start.required" => "This is required",
            "week_days.in" => "Week start day should be a valid calendar dayname. e.g.: monday",
            "show_weekdays.required" => "This is required",
            "show_weekdays.boolean" => "This accepts boolean only",
            "timeslot_size.required" => "This is required",

        ];
    }
}
