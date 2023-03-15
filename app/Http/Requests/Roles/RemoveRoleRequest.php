<?php

namespace App\Http\Requests\Roles;

use App\Models\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class RemoveRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'role_name' => 'required|exists:roles,name',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if role is not assigned to user
            if (! UserRole::where('role_name', $this->get('role_name'))->where('user_id', $this->get('user_id'))->exists()) {
                $validator->errors()->add('role_name', 'Role is not assigned to user');
            }
        });
    }

    public function messages()
    {
        return [
            'user_id.exists' => 'This user does not exist.',
            'role_name.exists' => 'This role does not exist.',
        ];
    }
}
