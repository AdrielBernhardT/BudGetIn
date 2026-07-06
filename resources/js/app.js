import './bootstrap';
import Alpine from 'alpinejs';
import ApexCharts from 'apexcharts';

// flatpickr
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import monthSelectPlugin from "flatpickr/dist/plugins/monthSelect";
import "flatpickr/dist/plugins/monthSelect/style.css";
// FullCalendar
import { Calendar } from '@fullcalendar/core';

import { createIcons, icons } from 'lucide';
import './push-notifications';


window.Alpine = Alpine;
window.ApexCharts = ApexCharts;
window.flatpickr = flatpickr;
window.monthSelectPlugin = monthSelectPlugin;
window.FullCalendar = Calendar;
window.createIcons = () => createIcons({ icons });;

// Register the Web Push service worker (subscription itself is opt-in, triggered from Settings)
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch((err) => console.error('SW registration failed', err));
}

// initial render
window.createIcons();

import statisticsChart from './components/chart/statisticsChart';
Alpine.data("statisticsChart", statisticsChart);
Alpine.start();

// Initialize components on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    // Map imports
    if (document.querySelector('#mapOne')) {
        import('./components/map').then(module => module.initMap());
    }

    // Chart imports
    if (document.querySelector('#chartOne')) {
        import('./components/chart/chart-1').then(module => module.initChartOne());
    }
    if (document.querySelector('#chartTwo')) {
        import('./components/chart/chart-2').then(module => module.initChartTwo());
    }
    if (document.querySelector('#chartThree')) {
        import('./components/chart/chart-3').then(module => module.initChartThree());
    }
    if (document.querySelector('#chartSix')) {
        import('./components/chart/chart-6').then(module => module.initChartSix());
    }
    if (document.querySelector('#chartEight')) {
        import('./components/chart/chart-8').then(module => module.initChartEight());
    }
    if (document.querySelector('#chartThirteen')) {
        import('./components/chart/chart-13').then(module => module.initChartThirteen());
    }

    if (document.querySelector('#targetPieChart')) {
        import('./components/chart/target-pie-chart').then(module => module.pieChart());
    }
    if (document.querySelector('#allocationChart')) {
        import('./components/chart/investment-allocation').then(module => module.allocationChart());
    }

    // Calendar init
    if (document.querySelector('#calendar')) {
        import('./components/calendar-init').then(module => module.calendarInit());
    }
});