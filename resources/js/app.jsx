import React from 'react';
import { createRoot } from 'react-dom/client';
import ContactForm from './components/ContactForm';
import axios from 'axios';

const csrfToken = document.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
}

const rootElement = document.getElementById('app');
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(<ContactForm />);
}