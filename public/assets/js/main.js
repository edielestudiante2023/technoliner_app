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

    // Carrusel del hero
    var carousel = document.getElementById('heroCarousel');
    if (carousel) {
        var slides = carousel.querySelectorAll('.hero-carousel-slide');
        var dots = carousel.querySelectorAll('.hero-carousel-dot');
        var current = 0;
        var timer = null;

        function mostrar(index) {
            slides[current].classList.remove('active');
            dots[current].classList.remove('active');
            current = index;
            slides[current].classList.add('active');
            dots[current].classList.add('active');
        }

        function siguiente() {
            mostrar((current + 1) % slides.length);
        }

        function iniciar() {
            timer = setInterval(siguiente, 4000);
        }

        function reiniciar() {
            clearInterval(timer);
            iniciar();
        }

        dots.forEach(function (dot, index) {
            dot.addEventListener('click', function () {
                mostrar(index);
                reiniciar();
            });
        });

        if (slides.length > 1) {
            iniciar();
        }
    }

    // Lightbox de la imagen del producto
    var imagenPrincipal = document.getElementById('imagen-principal');
    var lightbox = document.getElementById('lightbox');
    if (imagenPrincipal && lightbox) {
        var lightboxImg = document.getElementById('lightboxImg');
        var lightboxClose = document.getElementById('lightboxClose');

        function abrirLightbox() {
            lightboxImg.src = imagenPrincipal.src;
            lightboxImg.alt = imagenPrincipal.alt;
            lightbox.classList.add('open');
        }

        function cerrarLightbox() {
            lightbox.classList.remove('open');
        }

        imagenPrincipal.addEventListener('click', abrirLightbox);
        lightboxClose.addEventListener('click', cerrarLightbox);
        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox) cerrarLightbox();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') cerrarLightbox();
        });
    }
})();
