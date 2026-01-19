import './bootstrap';
import { inject } from '@vercel/analytics';

// Initialize Vercel Analytics with explicit production mode
inject({
    mode: 'production',
});