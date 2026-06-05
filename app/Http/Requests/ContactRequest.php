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
            'name' => 'required|min:3|max:50|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:contacts,email',
            'password' => 'required|min:6|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/',
            'confirm_password' => 'required|same:password',
            'message' => 'required|min:10|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is mandatory',
            'name.min' => 'Name must be at least 3 characters',
            'name.max' => 'Name cannot exceed 50 characters',
            'name.regex' => 'Name can only contain letters and spaces',
            'email.required' => 'Email is required',
            'email.email' => 'Enter valid email address',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'password.regex' => 'Password must contain at least one letter and one number',
            'confirm_password.same' => 'Password does not match',
            'message.required' => 'Message cannot be empty',
            'message.min' => 'Message must be at least 10 characters',
            'message.max' => 'Message cannot exceed 500 characters',
        ];
    }
}