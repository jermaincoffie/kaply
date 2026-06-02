import './bootstrap';
import Alpine from 'alpinejs';
import _ from 'lodash';

// Livewire 3 start Alpine zelf — window.Alpine instellen zodat plugins werken,
// maar NIET Alpine.start() aanroepen (dubbele instantie breekt @entangle)
window.Alpine = Alpine;

window._ = _;

const preline = await import('preline');
window.HSStaticMethods = preline.HSStaticMethods;

if (document.readyState === 'complete') {
    preline.HSStaticMethods.autoInit();
} else {
    window.addEventListener('load', () => {
        preline.HSStaticMethods.autoInit();
    });
}
