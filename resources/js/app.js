import './bootstrap';
import { Calendar } from 'vanilla-calendar-pro';
import _ from 'lodash';

// Preline datepicker gebruikt _ (lodash) globaal in constructor
window._ = _;
// Preline datepicker checkt window.VanillaCalendarPro
window.VanillaCalendarPro = Calendar;

const preline = await import('preline');
// Niet overschrijven — preline pre-bundle zet window.HSStaticMethods al correct
// met juiste closure naar VanillaCalendarPro. Overschrijven breekt autoInit(['datepicker']).

const initPreline = () => {
    if (window.HSStaticMethods) window.HSStaticMethods.autoInit();
};

if (document.readyState === 'complete') {
    initPreline();
} else {
    window.addEventListener('load', initPreline);
}

// Herinitialiseer na Livewire navigatie
document.addEventListener('livewire:navigated', initPreline);
