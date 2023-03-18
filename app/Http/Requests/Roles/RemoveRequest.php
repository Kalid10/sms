<?php

namespace App\Http\Requests\Roles;

use App\Models\UserRole;
use Illuminate\Foundation\Http\FormRequest;

class RemoveRequest extends FormRequest
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
            'roles' => 'required|array|min:1',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if role is not assigned to user from the roles array
            foreach ($this->get('roles') as $role) {
                if (! UserRole::where('role_name', $role)->where('user_id', $this->get('user_id'))->exists()) {
                    $validator->errors()->add('roles', 'Role '.$role.' is not assigned to this user.');
                }
            }
        });
    }

    public function messages()
    {
        return [
            'user_id.exists' => 'This user does not exist.',
        ];
    }
}
