    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-col footer-brand">
                <a href="<?= base_url('/') ?>" class="brand brand-footer">
                    <img src="<?= base_url('assets/img/logo-white.png') ?>" alt="Technoliner SAS" class="brand-logo">
                </a>
                <p>Soluciones de empaque seguras y sostenibles para las industrias alimentaria, farmacéutica, industrial y cosmética. Calidad que protege lo esencial.</p>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook">f</a>
                    <a href="#" aria-label="Instagram">ig</a>
                    <a href="#" aria-label="X">x</a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Navegación</h4>
                <ul>
                    <li><a href="<?= base_url('/') ?>#inicio">Inicio</a></li>
                    <li><a href="<?= base_url('/') ?>#nosotros">Nosotros</a></li>
                    <li><a href="<?= site_url('productos') ?>">Productos</a></li>
                    <li><a href="<?= site_url('blog') ?>">Blog</a></li>
                    <li><a href="<?= base_url('/') ?>#contacto">Contacto</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contacto</h4>
                <ul class="footer-contact">
                    <li>📞 <a href="tel:<?= esc(str_replace(' ', '', $empresa['telefono'])) ?>"><?= esc($empresa['telefono']) ?></a></li>
                    <li>✉️ <a href="mailto:<?= esc($empresa['correo']) ?>"><?= esc($empresa['correo']) ?></a></li>
                    <li>💬 <a href="https://wa.me/<?= esc($empresa['whatsapp_link']) ?>" target="_blank" rel="noopener">WhatsApp: <?= esc($empresa['whatsapp']) ?></a></li>
                    <li>📍 <?= esc($empresa['direccion']) ?></li>
                    <li>🏢 NIT: <?= esc($empresa['nit']) ?></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Legal</h4>
                <ul>
                    <li><a href="#">Política de privacidad</a></li>
                    <li><a href="#">Política de cookies</a></li>
                    <li><a href="#">Términos y condiciones</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p>&copy; <?= date('Y') ?> Technoliner SAS. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <a href="https://wa.me/<?= esc($empresa['whatsapp_link']) ?>" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Escríbenos por WhatsApp">💬</a>

    <script src="<?= base_url('assets/js/main.js') ?>"></script>
    <script>
        // Registro del Service Worker (PWA)
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('<?= base_url('sw.js') ?>')
                    .catch(function (err) { console.warn('SW no registrado:', err); });
            });
        }
    </script>
</body>
</html>
