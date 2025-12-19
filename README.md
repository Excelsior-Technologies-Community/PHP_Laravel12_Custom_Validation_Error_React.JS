# PHP_Laravel12_Custom_Validation_Error_React.js

This project demonstrates **Custom Validation Errors** using **Laravel 12 + React.js (Vite)** with **web routes (not API)**. It is beginner‑friendly and uses **JSX files only** for React.

---

## 1. Project Overview

We will build a **Contact Form** with:

* Name
* Email
* Password
* Confirm Password
* Message


---

## 2. Technologies Used

* PHP 8.2+
* Laravel 12
* MySQL
* React.js (Vite)
* Bootstrap 5
* Axios

---

## 3. Create Laravel Project

```
composer create-project laravel/laravel PHP_Laravel12_Custom_Validation_Error_React.JS
cd PHP_Laravel12_Custom_Validation_Error_React.JS
```

### Configure Database: 
Open the `.env` file and update the database credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=custom_validation_error_react.js
DB_USERNAME=root
DB_PASSWORD=
```

Create the database in MySQL:

```
CREATE DATABASE custom_validation_error_react.js;
```

---

## 4. Install React using Vite

```
npm install
npm install react react-dom
npm install --save-dev @vitejs/plugin-react
npm install axios bootstrap
```

### Update `vite.config.js`

```
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
    ],
});
```

---

## 5. Database Setup

### Create Migration

Run Command :

```
php artisan make:migration create_contacts_table
```

### Migration File

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
```
### Run:
```
php artisan migrate
```

---

## 6. Create Model

Run Command :

```
php artisan make:model Contact
```
Open model and write this:
```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'message'
    ];
}
```

---

## 7. Create Controller

Run Command:

```
php artisan make:controller ContactController
```

### Controller with Custom Validation

```
<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        // Custom validation
        $validated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:contacts,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
            'message' => 'required|min:10',
        ],[
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
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'message' => $request->message,
        ]);

        return response()->json(['success' => true]);
    }
}
```

---

## 8. Web Routes

```
<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;


Route::get('/', function () {
    return view('welcome');
});




Route::get('/', [ContactController::class, 'index']);
Route::post('/contact-store', [ContactController::class, 'store']);
```

---

## 9. Blade View

`resources/views/contact.blade.php`

```
<!DOCTYPE html>
<html>
<head>
    <title>Custom Validation</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @viteReactRefresh
    @vite('resources/js/app.jsx')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div id="app"></div>
</body>
</html>
```

---

## 10. React Entry File

`resources/js/app.jsx`

```
import React from 'react';
import { createRoot } from 'react-dom/client';
import ContactForm from './components/ContactForm';
import axios from 'axios';

// Set CSRF token for web routes
axios.defaults.headers.common['X-CSRF-TOKEN'] = 
    document.querySelector('meta[name="csrf-token"]').content;

createRoot(document.getElementById('app')).render(<ContactForm />);
```

---

## 11. React Component

`resources/js/components/ContactForm.jsx`

```
import React, { useState } from 'react';
import axios from 'axios';

export default function ContactForm() {
    const [form, setForm] = useState({
        name: '',
        email: '',
        password: '',
        confirm_password: '',
        message: ''
    });
    const [errors, setErrors] = useState({});
    const [success, setSuccess] = useState(false);

    const handleChange = e => {
        setForm({ ...form, [e.target.name]: e.target.value });
    };

    const submitForm = e => {
        e.preventDefault();
        setErrors({});
        setSuccess(false);

        axios.post('/contact-store', form)
            .then(() => {
                setSuccess(true);
                setForm({
                    name: '',
                    email: '',
                    password: '',
                    confirm_password: '',
                    message: ''
                });
            })
            .catch(err => {
                if (err.response.status === 422) {
                    setErrors(err.response.data.errors);
                }
            });
    };

    return (
        <div className="container mt-5">
            <div className="card shadow">
                <div className="card-header bg-primary text-white">
                    Custom Validation Form
                </div>
                <div className="card-body">

                    {success && <div className="alert alert-success">Form Submitted Successfully!</div>}

                    <form onSubmit={submitForm}>
                        <input 
                            className="form-control mb-2" 
                            name="name" 
                            placeholder="Name" 
                            value={form.name} 
                            onChange={handleChange} 
                        />
                        <small className="text-danger">{errors.name?.[0]}</small>

                        <input 
                            className="form-control mb-2" 
                            name="email" 
                            placeholder="Email" 
                            value={form.email} 
                            onChange={handleChange} 
                        />
                        <small className="text-danger">{errors.email?.[0]}</small>

                        <input 
                            type="password" 
                            className="form-control mb-2" 
                            name="password" 
                            placeholder="Password" 
                            value={form.password} 
                            onChange={handleChange} 
                        />
                        <small className="text-danger">{errors.password?.[0]}</small>

                        <input 
                            type="password" 
                            className="form-control mb-2" 
                            name="confirm_password" 
                            placeholder="Confirm Password" 
                            value={form.confirm_password} 
                            onChange={handleChange} 
                        />
                        <small className="text-danger">{errors.confirm_password?.[0]}</small>

                        <textarea 
                            className="form-control mb-2" 
                            name="message" 
                            placeholder="Message" 
                            value={form.message} 
                            onChange={handleChange}
                        ></textarea>
                        <small className="text-danger">{errors.message?.[0]}</small>

                        <button className="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    );
}
```

---

## 12. Run Project

```
php artisan serve
npm run dev
```

Open browser:

```
http://127.0.0.1:8000
```
### So you can see this type Output:

Error show:

<img width="1911" height="958" alt="Screenshot 2025-12-19 120915" src="https://github.com/user-attachments/assets/90a73cac-4e66-40dc-966b-f053f80627ca" />

<img width="1910" height="959" alt="Screenshot 2025-12-19 122638" src="https://github.com/user-attachments/assets/c7550225-cb21-4bfc-b636-8f1c5a4e4bba" />

After submit form show Message:

<img width="1915" height="965" alt="Screenshot 2025-12-19 122449" src="https://github.com/user-attachments/assets/a0c6294b-1c7f-4b77-bc80-da31bcbc6cd9" />


<img width="1919" height="962" alt="Screenshot 2025-12-19 122353" src="https://github.com/user-attachments/assets/69b2bb08-0058-49b4-8ef9-4b6c17e4a920" />


---

## PHP_Laravel12_Custom_Validation_Error_React.js – Folder Structure

```
PHP_Laravel12_Custom_Validation_Error_React.JS/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ContactController.php
│   │   └── ...
│   ├── Models/
│   │   └── Contact.php
│   └── ...
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   │   └── xxxx_xx_xx_create_contacts_table.php
│   └── ...
├── node_modules/
├── public/
│   ├── index.php
│   └── ...
├── resources/
│   ├── js/
│   │   ├── app.jsx
│   │   └── components/
│   │       └── ContactForm.jsx
│   ├── views/
│   │   └── contact.blade.php
│   └── ...
├── routes/
│   └── web.php
├── vendor/
├── .env
├── composer.json
├── package.json
├── vite.config.js
└── README.md


