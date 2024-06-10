import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';


menuToggle = document.querySelector('.nav-container input[type="checkbox"]');
var btnGroup = document.querySelectorAll('.btn-group');
if (menuToggle) {
    menuToggle.addEventListener('change', function() {
        if (menuToggle.checked) {
            btnGroup.forEach(function(btn) {
                btn.classList.add('hidden');
            });
        } else {
            btnGroup.forEach(function(btn) {
                btn.classList.remove('hidden');
            });
        }
    });
}