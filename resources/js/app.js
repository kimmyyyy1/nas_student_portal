import './bootstrap';

// 👇 ADD THIS: Import and inject Vercel Analytics
import { inject } from '@vercel/analytics';

// Initialize Analytics immediately
inject();

// ❌ TANGGALIN NA ANG MGA ITO (Dahil automatic na ito sa Livewire 3):
// import Alpine from 'alpinejs';
// window.Alpine = Alpine;
// Alpine.start();