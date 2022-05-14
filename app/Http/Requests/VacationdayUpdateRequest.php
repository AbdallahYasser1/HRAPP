<?php

namespace App\Http\Requests;

use App\Models\Vacationday;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class VacationdayUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasPermissionTo('update_Vacationday');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Vacationday::Validation_Update_Vacation_Day;
    }
}
