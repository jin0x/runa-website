import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

// Simple domReady function instead of using the WordPress package
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

gsap.registerPlugin(ScrollTrigger);

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

domReady(() => {
  console.log('app.js');
  //
  window.Swiper = Swiper;
  window.Alpine = Alpine;
  window.gsap = gsap;
  window.ScrollTrigger = ScrollTrigger;
  Alpine.start();
});
