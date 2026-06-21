/**
 * SIM UKM Jurnalistik
 * Politeknik Negeri Samarinda
 *
 * Main JavaScript Entry Point
 */

// Bootstrap 5
import * as bootstrap from 'bootstrap';

// Alpine.js
import Alpine from 'alpinejs';

// Chart.js
import Chart from 'chart.js/auto';

// Axios (HTTP Client)
import axios from 'axios';

// ── Make available globally ──────────────────────
window.bootstrap = bootstrap;
window.Alpine = Alpine;
window.Chart = Chart;
window.axios = axios;

// ── Axios defaults ───────────────────────────────
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// ── Initialize Alpine.js ─────────────────────────
Alpine.start();
