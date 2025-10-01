import.meta.glob(['../images/**', '../fonts/**']);

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
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

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
        autoplay: options.autoplayDelay
          ? {
              delay: options.autoplayDelay,
              disableOnInteraction: false,
            }
          : false,
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
        this.swiper.autoplay.start();
      }
    }
  },

  destroy() {
    if (this.swiper) {
      this.swiper.destroy(true, true);
    }
  },
}));

// Alpine component for FAQ accordion
Alpine.data('faqAccordion', () => ({
  openItems: [],

  toggle(index) {
    if (this.isItemOpen(index)) {
      this.openItems = this.openItems.filter((i) => i !== index);
    } else {
      this.openItems.push(index);
    }
  },

  isItemOpen(index) {
    return this.openItems.includes(index);
  },
}));

domReady(() => {
  // Make globally available BEFORE Alpine starts
  window.Swiper = Swiper;
  window.gsap = gsap;
  window.ScrollTrigger = ScrollTrigger;

  // Test GSAP
  if (typeof gsap !== 'undefined') {
    gsap.set('body', { opacity: 1 });
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
        isInitialized: false,
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
        } catch (error) {
          console.error(`GSAP component failed: ${componentId}`, error);
        }
      }
    },

    // Initialize all registered components
    initializeAll() {
      if (!window.gsap) {
        return;
      }

      this.isReady = true;

      for (const [componentId] of this.components) {
        this.initializeComponent(componentId);
      }
    },

    // Cleanup all animations (for page transitions, etc.)
    cleanup() {
      this.cleanupFunctions.forEach((cleanup) => {
        try {
          cleanup();
        } catch (error) {
          // Silent cleanup errors
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
        ease = 'power2.out',
        ...animationProps
      } = animation;

      return window.gsap.to(targets, {
        ...animationProps,
        ease,
        stagger,
        ...options,
      });
    },
  };

  // Make GSAPManager globally available
  window.GSAPManager = GSAPManager;

  // Start Alpine with error handling
  try {
    Alpine.start();

    // Initialize GSAP components after Alpine is ready
    GSAPManager.initializeAll();
  } catch (e) {
    console.error('Alpine start failed:', e);
  }
});
