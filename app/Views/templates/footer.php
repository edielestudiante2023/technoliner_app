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

    <a href="https://wa.me/<?= esc($empresa['whatsapp_link']) ?>" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Escríbenos por WhatsApp">
        <svg viewBox="0 0 32 32" width="32" height="32" fill="#fff" aria-hidden="true"><path d="M16.001 3C9.383 3 4 8.383 4 15c0 2.386.703 4.61 1.912 6.478L4 29l7.72-1.874A11.94 11.94 0 0 0 16.001 27C22.617 27 28 21.617 28 15S22.617 3 16.001 3zm0 21.818a9.77 9.77 0 0 1-4.98-1.363l-.357-.213-4.583 1.112 1.127-4.47-.232-.366A9.78 9.78 0 0 1 6.182 15c0-5.42 4.4-9.818 9.819-9.818S25.818 9.58 25.818 15 21.42 24.818 16.001 24.818zm5.393-7.36c-.295-.148-1.746-.862-2.017-.96-.271-.099-.469-.148-.667.148-.197.295-.766.96-.939 1.157-.173.198-.345.222-.64.074-.295-.148-1.246-.459-2.374-1.464-.877-.782-1.469-1.748-1.641-2.043-.173-.296-.019-.456.13-.603.133-.132.296-.345.444-.518.148-.173.197-.296.296-.494.099-.198.05-.37-.025-.518-.074-.148-.667-1.608-.914-2.202-.24-.577-.485-.5-.667-.51-.173-.008-.37-.01-.568-.01a1.09 1.09 0 0 0-.79.37c-.271.296-1.037 1.014-1.037 2.474 0 1.46 1.062 2.87 1.21 3.068.148.198 2.09 3.194 5.065 4.478.708.306 1.26.489 1.69.626.71.226 1.356.194 1.867.118.57-.085 1.746-.714 1.993-1.403.247-.69.247-1.28.173-1.403-.074-.123-.271-.198-.567-.346z"/></svg>
    </a>

    <script src="<?= base_url('assets/js/main.js') ?>?v=<?= filemtime(FCPATH . 'assets/js/main.js') ?>"></script>
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
