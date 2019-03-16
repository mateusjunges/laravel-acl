<?php

namespace MateusJunges\ACL\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

class UserRequest extends FormRequest
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
        $id = 'NULL';
        if (Route::getCurrentRoute()->parameters() != null)
            $id = Route::getCurrentRoute()->parameters()['user'];
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$id,
            'username' => 'required|min:3|unique:users,username,'.$id,
        ];
        if (config('acl.user.withPassword'))
            $rules += [
              'password' => 'required|min:6',
            ];

        return $rules;
    }

    /**
     * Form validation error messages
     * @return array
     */
    public function messages()
    {
        $messages = [
            '*.required' => 'Este campo é obrigatório!',
            'email.unique' => 'Este email já está em uso!',
            'username.unique' => 'Este nome de usuário já está em uso!',
        ];

        return $messages;
    }
}
