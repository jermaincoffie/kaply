import './bootstrap';
import { Calendar } from 'vanilla-calendar-pro';

// Preline datepicker checkt window.VanillaCalendarPro
window.VanillaCalendarPro = Calendar;

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
