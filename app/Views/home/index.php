<?= $this->include('templates/header') ?>

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="container hero-inner">
        <div class="hero-text">
            <span class="badge">+15 años de experiencia</span>
            <h1>Technoliner SAS</h1>
            <p class="hero-tagline"><?= esc($empresa['eslogan']) ?></p>
            <p class="hero-desc"><?= esc($empresa['descripcion']) ?></p>
            <div class="hero-actions">
                <a href="#productos" class="btn btn-primary btn-lg">Conoce nuestros productos →</a>
                <a href="#contacto" class="btn btn-outline btn-lg">Contáctanos</a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-card hero-card-1"><img src="<?= base_url('assets/img/hero-empaques.jpg') ?>" alt="Empaques"><span>Empaques</span></div>
            <div class="hero-card hero-card-2"><img src="<?= base_url('assets/img/hero-sostenible.jpg') ?>" alt="Sostenible"><span>Sostenible</span></div>
            <div class="hero-card hero-card-3"><img src="<?= base_url('assets/img/hero-seguro.jpg') ?>" alt="Seguro"><span>Seguro</span></div>
        </div>
    </div>
</section>

<!-- ===== NOSOTROS / POR QUÉ ELEGIRNOS ===== -->
<section class="section" id="nosotros">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Nosotros</span>
            <h2>¿Por qué elegir Technoliner?</h2>
        </div>
        <div class="about-grid">
            <div class="about-text">
                <p>Contamos con experiencia en el mercado ofreciendo <strong>soluciones de empaque confiables</strong> para las industrias alimentaria, farmacéutica, industrial y cosmética.</p>
                <p>Brindamos un servicio personalizado que acompaña a nuestros clientes desde la asesoría y el diseño, hasta la fabricación y la entrega, garantizando calidad en cada etapa del proceso.</p>
                <div class="about-stats">
                    <div class="stat"><strong>+15</strong><span>años de experiencia</span></div>
                    <div class="stat"><strong>4</strong><span>industrias atendidas</span></div>
                    <div class="stat"><strong>100%</strong><span>compromiso con la calidad</span></div>
                </div>
            </div>
            <div class="about-visual">
                <div class="about-image">🏭</div>
            </div>
        </div>
    </div>
</section>

<!-- ===== BENEFICIOS ===== -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Beneficios</span>
            <h2>Nuestra propuesta de valor</h2>
        </div>
        <div class="benefits-grid">
            <article class="benefit-card">
                <div class="benefit-icon">🔒</div>
                <h3>Seguridad y protección</h3>
                <p>Ofrecemos soluciones de empaque con diferentes niveles de protección para adaptarse a las necesidades de cada producto.</p>
            </article>
            <article class="benefit-card">
                <div class="benefit-icon">♻️</div>
                <h3>Sostenibilidad</h3>
                <p>Trabajamos con materiales reciclables y procesos eficientes que reducen el impacto ambiental.</p>
            </article>
            <article class="benefit-card">
                <div class="benefit-icon">⚙️</div>
                <h3>Personalización</h3>
                <p>Diseñamos soluciones a la medida que se ajustan a los requerimientos específicos de tu producto.</p>
            </article>
        </div>
    </div>
</section>

<!-- ===== PRODUCTOS ===== -->
<section class="section" id="productos">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Productos</span>
            <h2>Productos destacados</h2>
            <p class="section-sub">Conoce algunas de nuestras soluciones de empaque y cierre.</p>
        </div>
        <div class="products-grid">
            <article class="product-card">
                <div class="product-img product-img-1">🟦</div>
                <div class="product-body">
                    <h3>Tapa plana o lisa</h3>
                    <p>La tapa sencilla de 38 mm, también conocida como tapa lisa o plana, es una solución de cierre versátil y fiable, fabricada con polipropileno de alta densidad.</p>
                    <a href="#contacto" class="link-arrow">Ver detalles →</a>
                </div>
            </article>
            <article class="product-card">
                <div class="product-img product-img-2">🟩</div>
                <div class="product-body">
                    <h3>Pote crema + tapa</h3>
                    <p>El pote crema con tapa es la elección ideal para almacenar y mantener la frescura de tus cremas y productos cosméticos.</p>
                    <a href="#contacto" class="link-arrow">Ver detalles →</a>
                </div>
            </article>
            <article class="product-card">
                <div class="product-img product-img-3">🟧</div>
                <div class="product-body">
                    <h3>Tapa push down 38 mm</h3>
                    <p>La tapa push down de 38 mm es una solución de cierre eficiente y segura, ideal para una variedad de aplicaciones.</p>
                    <a href="#contacto" class="link-arrow">Ver detalles →</a>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- ===== TESTIMONIOS ===== -->
<section class="section section-alt">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Testimonios</span>
            <h2>Lo que dicen nuestros clientes</h2>
        </div>
        <div class="testimonials-grid">
            <article class="testimonial-card">
                <p class="quote">“Trabajar con Technoliner ha sido una excelente experiencia. Siempre están dispuestos a adaptarse a nuestras necesidades y la calidad de sus productos es excepcional.”</p>
                <div class="testimonial-author">
                    <span class="avatar">N</span>
                    <div><strong>Natural Fresshly</strong><small>Laboratorio · Tienda naturista</small></div>
                </div>
            </article>
            <article class="testimonial-card">
                <p class="quote">“Las tapas cumplen con altos estándares de calidad y sostenibilidad. Un proveedor confiable que nos da tranquilidad en cada pedido.”</p>
                <div class="testimonial-author">
                    <span class="avatar">A</span>
                    <div><strong>Ana Martínez</strong><small>Gerente de Calidad</small></div>
                </div>
            </article>
            <article class="testimonial-card">
                <p class="quote">“Los liners sensitivos mantienen la frescura de los alimentos y siempre contamos con soporte técnico disponible. Totalmente recomendados.”</p>
                <div class="testimonial-author">
                    <span class="avatar">L</span>
                    <div><strong>Laura Sánchez</strong><small>Directora de Compras</small></div>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- ===== CONTACTO ===== -->
<section class="section section-contact" id="contacto">
    <div class="container contact-grid">
        <div class="contact-info">
            <span class="eyebrow eyebrow-light">Contacto</span>
            <h2>Hablemos de tu proyecto</h2>
            <p>Cuéntanos qué necesitas y nuestro equipo te asesorará para encontrar la mejor solución de empaque.</p>
            <ul class="contact-list">
                <li><span>📞</span> <a href="tel:<?= esc(str_replace(' ', '', $empresa['telefono'])) ?>"><?= esc($empresa['telefono']) ?></a></li>
                <li><span>✉️</span> <a href="mailto:<?= esc($empresa['correo']) ?>"><?= esc($empresa['correo']) ?></a></li>
                <li><span>💬</span> <a href="https://wa.me/<?= esc($empresa['whatsapp_link']) ?>" target="_blank" rel="noopener">WhatsApp: <?= esc($empresa['whatsapp']) ?></a></li>
                <li><span>📍</span> <?= esc($empresa['direccion']) ?></li>
            </ul>
        </div>

        <form class="contact-form" action="#" method="post" onsubmit="return false;">
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
                </div>
                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" placeholder="correo@empresa.com" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="telefono">Número de teléfono</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="+57 300 000 0000">
                </div>
                <div class="form-group">
                    <label for="empresa">Nombre de la empresa</label>
                    <input type="text" id="empresa" name="empresa" placeholder="Tu empresa">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="sector">Sector</label>
                    <select id="sector" name="sector">
                        <option value="">Selecciona…</option>
                        <option>Alimentos</option>
                        <option>Farmacéutico</option>
                        <option>Industrial</option>
                        <option>Cosmético</option>
                        <option>Otros</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="producto">Tipo de producto</label>
                    <select id="producto" name="producto">
                        <option value="">Selecciona…</option>
                        <option>Tapas plásticas</option>
                        <option>Liners por inducción</option>
                        <option>Liners sensitivos</option>
                        <option>Otros</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="mensaje">Mensaje o consulta</label>
                <textarea id="mensaje" name="mensaje" rows="4" placeholder="Cuéntanos en qué podemos ayudarte"></textarea>
            </div>
            <label class="form-check">
                <input type="checkbox" name="politica" required>
                <span>Acepto la política de tratamiento de datos personales.</span>
            </label>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Enviar mensaje</button>
            <p class="form-note">* El envío del formulario se habilitará próximamente.</p>
        </form>
    </div>
</section>

<?= $this->include('templates/footer') ?>
