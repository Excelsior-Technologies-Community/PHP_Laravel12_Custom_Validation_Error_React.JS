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

    // Validate field dynamically
    const validateField = (name, value) => {
        let error = '';

        switch(name) {
            case 'name':
                if(!value) error = 'Name is mandatory';
                else if(value.length < 3) error = 'Name must be at least 3 characters';
                break;
            case 'email':
                if(!value) error = 'Email is required';
                else if(!/\S+@\S+\.\S+/.test(value)) error = 'Enter valid email address';
                break;
            case 'password':
                if(!value) error = 'Password is required';
                else if(value.length < 6) error = 'Password must be 6 characters';
                break;
            case 'confirm_password':
                if(value !== form.password) error = 'Password does not match';
                break;
            case 'message':
                if(!value) error = 'Message cannot be empty';
                else if(value.length < 10) error = 'Message must be 10 characters';
                break;
        }

        setErrors(prev => ({ ...prev, [name]: error }));
    };

    const handleChange = e => {
        const { name, value } = e.target;
        setForm(prev => ({ ...prev, [name]: value }));

        // Real-time validation on typing
        validateField(name, value);
    };

    const passwordStrength = password => {
        if(!password) return '';
        if(password.length < 6) return 'Weak';
        if(password.match(/[A-Z]/) && password.match(/[0-9]/)) return 'Strong';
        return 'Medium';
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
                if(err.response.status === 422){
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

                    {success && (
                        <div className="alert alert-success">Form Submitted Successfully!</div>
                    )}

                    <form onSubmit={submitForm}>
                        {/* Name */}
                        <input 
                            className="form-control mb-1" 
                            name="name" 
                            placeholder="Name" 
                            value={form.name} 
                            onChange={handleChange} 
                        />
                        <small className="text-danger d-block mb-2">{errors.name}</small>

                        {/* Email */}
                        <input 
                            className="form-control mb-1" 
                            name="email" 
                            placeholder="Email" 
                            value={form.email} 
                            onChange={handleChange} 
                        />
                        <small className="text-danger d-block mb-2">{errors.email}</small>

                        {/* Password */}
                        <input 
                            type="password" 
                            className="form-control mb-1" 
                            name="password" 
                            placeholder="Password" 
                            value={form.password} 
                            onChange={handleChange} 
                        />
                        <small className="text-danger d-block mb-1">{errors.password}</small>
                        <small className="text-info d-block mb-2">Strength: {passwordStrength(form.password)}</small>

                        {/* Confirm Password */}
                        <input 
                            type="password" 
                            className="form-control mb-1" 
                            name="confirm_password" 
                            placeholder="Confirm Password" 
                            value={form.confirm_password} 
                            onChange={handleChange} 
                        />
                        <small className="text-danger d-block mb-2">{errors.confirm_password}</small>

                        {/* Message */}
                        <textarea 
                            className="form-control mb-1" 
                            name="message" 
                            placeholder="Message" 
                            value={form.message} 
                            onChange={handleChange} 
                        ></textarea>
                        <small className="text-danger d-block mb-3">{errors.message}</small>

                        {/* Submit Button */}
                        <button className="btn btn-primary mt-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    );
}