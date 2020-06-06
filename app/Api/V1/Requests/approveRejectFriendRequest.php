<?php

namespace App\Api\V1\Requests;

use App\Models\Friend;
use App\Models\User;

use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule as ValidationRule;

class approveRejectFriendRequest extends FormRequest
{
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
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'status' => ['required', ValidationRule::in(array_keys(Friend::$statuses))],
        ];
    }

}
