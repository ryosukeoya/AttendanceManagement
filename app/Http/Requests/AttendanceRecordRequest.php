<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class AttendanceRecordRequest extends FormRequest
{
    protected $redirectRoute = 'attendance_record.start';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $now = new Carbon();
        return [
            'time' => "required | before_or_equal:{$now}",
        ];
    }
}