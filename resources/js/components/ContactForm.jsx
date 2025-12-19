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
