<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:contacts,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'message' => 'required|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is mandatory',
            'name.min' => 'Name must be at least 3 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Enter valid email address',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be 6 characters',
            'confirm_password.same' => 'Password does not match',
            'message.required' => 'Message cannot be empty',
            'message.min' => 'Message must be 10 characters',
        ];
    }
}