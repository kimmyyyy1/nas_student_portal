import './bootstrap';

// 1. Import Packages
import { inject } from '@vercel/analytics';
import { injectSpeedInsights } from '@vercel/speed-insights';

// 2. Initialize Web Analytics (Visitor Tracking)
inject({
    mode: 'production',
});

// 3. Initialize Speed Insights (Performance Tracking)
injectSpeedInsights();