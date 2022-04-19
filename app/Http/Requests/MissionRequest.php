<?php

namespace App\Http\Requests;
use App\Models\Mission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
class MissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasPermissionTo('create_mission');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Mission::Validation_Rules;
    }
}
