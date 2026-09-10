import './bootstrap';
import Alpine from 'alpinejs';
import './rol-watcher.js';
import { initTheme, toggleTheme } from './theme-switcher';

window.toggleTheme = toggleTheme;
initTheme();
window.Alpine = Alpine;
document.addEventListener('alpine:init', () => {
    Alpine.store('sidebar', {
        isCollapsed: false,
        isOpen: false,
        toggleCollapsed() { this.isCollapsed = !this.isCollapsed },
        toggleOpen() { this.isOpen = !this.isOpen }
    });
});
Alpine.start();

import { initAuroraParticles } from './aurora-particles';

// ...donde ya tienes tu código de inicialización, junto a Alpine.start() por ejemplo:
initAuroraParticles();