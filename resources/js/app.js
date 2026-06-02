import './bootstrap';
import Alpine from 'alpinejs';
import _ from 'lodash';

window.Alpine = Alpine;
Alpine.start();

window._ = _;

// Dynamic import ensures preline evaluates AFTER the assignment above.
const preline = await import('preline');

window.HSStaticMethods = preline.HSStaticMethods;

// Preline laden is asynchroon — als 'load' al gevuurd heeft, direct initialiseren
if (document.readyState === 'complete') {
    preline.HSStaticMethods.autoInit();
} else {
    window.addEventListener('load', () => {
        preline.HSStaticMethods.autoInit();
    });
}
