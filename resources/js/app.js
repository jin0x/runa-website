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

// Alpine component for carousels/sliders
Alpine.data('carouselComponent', (options = {}) => ({
  swiper: null,

  initSwiper() {
    if (typeof Swiper !== 'undefined') {
      // Default configuration
      const config = {
        loop: options.loop !== false,
        autoplay: options.autoplayDelay ? {
          delay: options.autoplayDelay,
          disableOnInteraction: false,
        } : false,
        slidesPerView: options.slidesPerView || 1,
        spaceBetween: options.spaceBetween || 20,
        breakpoints: {
          640: { slidesPerView: options.mobileSlidesPerView || 1 },
          768: { slidesPerView: options.tabletSlidesPerView || 1 },
          1024: { slidesPerView: options.desktopSlidesPerView || 1 },
        },
      };

      this.swiper = new Swiper(this.$refs.container, config);

      if (this.swiper.autoplay && config.autoplay) {
        console.log("▶️ Swiper autoplay started");
        this.swiper.autoplay.start();
      }

      console.log("✅ Swiper initialized successfully");
    } else {
      console.error('❌ Swiper library not loaded');
    }
  },

  destroy() {
    if (this.swiper) {
      this.swiper.destroy(true, true);
    }
  }
}));

// Alpine component for FAQ accordion
Alpine.data('faqAccordion', () => ({
  openItems: [],

  toggle(index) {
    if (this.isItemOpen(index)) {
      this.openItems = this.openItems.filter(i => i !== index);
    } else {
      this.openItems.push(index);
    }
    console.log(`FAQ item ${index} toggled, open items:`, this.openItems);
  },

  isItemOpen(index) {
    return this.openItems.includes(index);
  }
}));



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

  // Enhanced Alpine debugging
  function checkForComponents(label) {
    const components = document.querySelectorAll('[x-data]');
    console.log(`🔍 ${label}: Found ${components.length} Alpine components`);

    components.forEach((el, i) => {
      const xData = el.getAttribute('x-data');
      const hasAlpine = el._x_dataStack || el.__x;
      console.log(`  Component ${i}: ${hasAlpine ? '✅' : '❌'} ${xData} (${el.tagName})`);
    });

    return components.length;
  }

  // Check before Alpine starts
  checkForComponents('BEFORE Alpine.start()');

  // Start Alpine with error handling
  try {
    Alpine.start();
    console.log('✅ Alpine started successfully');

    // Check immediately after start
    checkForComponents('IMMEDIATELY after Alpine.start()');

    // Initialize GSAP components after Alpine is ready
    GSAPManager.initializeAll();

    // Check periodically for late-loading components
    setTimeout(() => checkForComponents('100ms after start'), 100);
    setTimeout(() => checkForComponents('500ms after start'), 500);
    setTimeout(() => checkForComponents('1000ms after start'), 1000);
    setTimeout(() => checkForComponents('2000ms after start'), 2000);

    // Monitor for new nodes being added to DOM
    if (typeof MutationObserver !== 'undefined') {
      const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
          if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
            mutation.addedNodes.forEach((node) => {
              if (node.nodeType === 1) { // Element node
                const hasXData = node.querySelector && node.querySelector('[x-data]');
                const isXData = node.getAttribute && node.getAttribute('x-data');

                if (hasXData || isXData) {
                  console.log('🔄 New Alpine component detected in DOM, attempting initTree');
                  try {
                    Alpine.initTree(node);
                  } catch (e) {
                    console.error('❌ initTree failed:', e);
                  }
                }
              }
            });
          }
        });
      });

      observer.observe(document.body, {
        childList: true,
        subtree: true
      });

      console.log('👀 MutationObserver watching for new Alpine components');
    }

  } catch (e) {
    console.error('❌ Alpine start failed:', e);
  }
});
