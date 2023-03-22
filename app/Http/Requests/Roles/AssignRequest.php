<?php

namespace App\Http\Requests\Roles;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class AssignRequest extends FormRequest
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
            // Check if role exists
            foreach ($this->get('roles') as $role) {
                if (! Role::where('name', $role)->first()) {
                    $validator->errors()->add('roles', 'Role '.$role.' does not exist.');
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
