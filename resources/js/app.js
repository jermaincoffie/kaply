import './bootstrap';
import Alpine from 'alpinejs';
import _ from 'lodash';

// Livewire 3 start Alpine zelf — window.Alpine instellen zodat plugins werken,
// maar NIET Alpine.start() aanroepen (dubbele instantie breekt @entangle)
window.Alpine = Alpine;

window._ = _;

const preline = await import('preline');
window.HSStaticMethods = preline.HSStaticMethods;

// HSDatepicker global beschikbaar voor x-datepicker component
if (preline.HSDatepicker) window.HSDatepicker = preline.HSDatepicker;

const initPreline = () => preline.HSStaticMethods.autoInit();

if (document.readyState === 'complete') {
    initPreline();
} else {
    window.addEventListener('load', initPreline);
}

// Herinitialiseer na Livewire navigatie
document.addEventListener('livewire:navigated', initPreline);
