// Alpine.js llega incluido con Livewire 4: no hace falta importarlo aquí.
import { zonaMapa } from './zona';

document.addEventListener('alpine:init', () => {
    window.Alpine.data('zonaMapa', zonaMapa);
});
