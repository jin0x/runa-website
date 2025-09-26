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

  // GSAP Manager - Centralized animation management
  const GSAPManager = {
    components: new Map(),
    isReady: false,
    cleanupFunctions: new Set(),

    // Register a component for GSAP animation
    register(componentId, initFunction, cleanupFunction = null) {
      this.components.set(componentId, {
        init: initFunction,
        cleanup: cleanupFunction,
        isInitialized: false
      });

      if (this.isReady) {
        this.initializeComponent(componentId);
      }
    },

    // Initialize a specific component
    initializeComponent(componentId) {
      const component = this.components.get(componentId);
      if (component && !component.isInitialized) {
        try {
          component.init();
          component.isInitialized = true;
          console.log(`✅ GSAP component initialized: ${componentId}`);
        } catch (error) {
          console.error(`❌ GSAP component failed: ${componentId}`, error);
        }
      }
    },

    // Initialize all registered components
    initializeAll() {
      if (!window.gsap) {
        console.error('❌ GSAP not available');
        return;
      }

      this.isReady = true;
      console.log('🚀 Initializing GSAP components...');

      for (const [componentId] of this.components) {
        this.initializeComponent(componentId);
      }
    },

    // Cleanup all animations (for page transitions, etc.)
    cleanup() {
      this.cleanupFunctions.forEach(cleanup => {
        try {
          cleanup();
        } catch (error) {
          console.error('Cleanup error:', error);
        }
      });
      this.cleanupFunctions.clear();

      // Kill all GSAP animations
      if (window.gsap) {
        window.gsap.killTweensOf('*');
        if (window.ScrollTrigger) {
          window.ScrollTrigger.killAll();
        }
      }
    },

    // Add a cleanup function
    addCleanup(cleanupFunction) {
      this.cleanupFunctions.add(cleanupFunction);
    },

    // Batch animation helper for performance
    batch(targets, animation, options = {}) {
      if (!window.gsap) return;

      const {
        stagger = 0.1,
        ease = "power2.out",
        ...animationProps
      } = animation;

      return window.gsap.to(targets, {
        ...animationProps,
        ease,
        stagger,
        ...options
      });
    }
  };

  // Make GSAPManager globally available
  window.GSAPManager = GSAPManager;

  // Start Alpine with error handling
  try {
    Alpine.start();
    console.log('✅ Alpine started successfully');

    // Initialize GSAP components after Alpine is ready
    GSAPManager.initializeAll();

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
