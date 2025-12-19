import React from 'react';
import { createRoot } from 'react-dom/client';
import ContactForm from './components/ContactForm';
import axios from 'axios';

// Set CSRF token for web routes
axios.defaults.headers.common['X-CSRF-TOKEN'] = 
    document.querySelector('meta[name="csrf-token"]').content;

createRoot(document.getElementById('app')).render(<ContactForm />);
