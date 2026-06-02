// Technoliner SAS — interacciones básicas
(function () {
    'use strict';

    var toggle = document.getElementById('navToggle');
    var nav = document.getElementById('mainNav');

    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            nav.classList.toggle('open');
        });

        // Cerrar el menú al hacer clic en un enlace (móvil)
        nav.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                nav.classList.remove('open');
            });
        });
    }
})();
