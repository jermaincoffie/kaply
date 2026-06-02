import './bootstrap';
import Alpine from 'alpinejs';
import _ from 'lodash';
import * as VanillaCalendarProModule from 'vanilla-calendar-pro';
import 'vanilla-calendar-pro/styles/layout.css';
import 'vanilla-calendar-pro/styles/themes/light.css';
import 'vanilla-calendar-pro/styles/themes/dark.css';

// Preline's datepicker checks window.VanillaCalendarPro
window.VanillaCalendarPro = VanillaCalendarProModule.VanillaCalendarPro
    ?? VanillaCalendarProModule.Calendar
    ?? VanillaCalendarProModule.default
    ?? VanillaCalendarProModule;

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
