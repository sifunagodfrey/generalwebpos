import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
// Register Sales components before Alpine initializes any x-data.
import '../../public/js/suweka-sales.js';
Alpine.start();
