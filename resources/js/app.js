import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

// Global error handler
window.addEventListener('error', (e) => {
  console.error('Global Error:', e.message, e);
});

window.addEventListener('unhandledrejection', (e) => {
  console.error('Unhandled Promise Rejection:', e.reason);
});

// Simple domReady function
const domReady = (callback) => {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', callback);
  } else {
    callback();
  }
};

import '@ryangjchandler/spruce';
import 'alpine-turbo-drive-adapter';
import Alpine from 'alpinejs';
import {gsap} from "gsap";
import {ScrollTrigger} from "gsap/ScrollTrigger";

// Fix for Swiper imports
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';

// Initialize Swiper with modules
Swiper.use([Navigation, Pagination, Autoplay]);

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger);

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

// Register Alpine components BEFORE Alpine.start()
window.Alpine = Alpine;



domReady(() => {
  console.log('=== APP.JS INITIALIZATION ===');
  console.log('Document state:', document.readyState);
  console.log('GSAP loaded:', typeof gsap !== 'undefined');
  console.log('ScrollTrigger loaded:', typeof ScrollTrigger !== 'undefined');
  console.log('Alpine loaded:', typeof Alpine !== 'undefined');
  console.log('Swiper loaded:', typeof Swiper !== 'undefined');

  // Make globally available BEFORE Alpine starts
  window.Swiper = Swiper;
  window.gsap = gsap;
  window.ScrollTrigger = ScrollTrigger;

  // Test GSAP
  if (typeof gsap !== 'undefined') {
    console.log('GSAP Version:', gsap.version);
    try {
      gsap.set('body', { opacity: 1 });
      console.log('GSAP test successful');
    } catch (e) {
      console.error('GSAP test failed:', e);
    }
  }

  // Start Alpine with error handling
  try {
    Alpine.start();
    console.log('✅ Alpine started successfully');

    // Test Alpine after start
    setTimeout(() => {
      const alpineComponents = document.querySelectorAll('[x-data]');
      console.log(`Found ${alpineComponents.length} Alpine components`);
      alpineComponents.forEach((el, i) => {
        console.log(`Component ${i}:`, el.tagName, el.className.slice(0, 50));
      });
    }, 100);
  } catch (e) {
    console.error('❌ Alpine start failed:', e);
  }
});
