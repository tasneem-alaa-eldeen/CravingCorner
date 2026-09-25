import Alpine from 'alpinejs';
import { Chart, registerables } from 'chart.js';

window.Alpine = Alpine;
Alpine.start();

// Bundled locally (via npm) instead of a CDN <script> tag, so the
// statistics / dashboard charts still render with no internet access.
Chart.register(...registerables);
window.Chart = Chart;
