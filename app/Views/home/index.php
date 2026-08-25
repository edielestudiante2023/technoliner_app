<?= $this->include('templates/header') ?>

<!-- ===== HERO ===== -->
<section class="hero">
    <div class="container hero-inner">
        <div class="hero-text">
            <span class="badge">+15 años de experiencia</span>
            <h1>Liners y soluciones de sellado para envases</h1>
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
                <p>Si Technoliner dejara de existir, el mercado perdería un aliado especializado en soluciones de sellado y protección para envases, capaz de adaptarse a las necesidades específicas de cada cliente.</p>
                <p>No solo comercializamos un producto: aportamos soluciones que ayudan a garantizar la <strong>integridad, seguridad, conservación y presentación</strong> de los productos envasados de nuestros clientes en las industrias alimentaria, farmacéutica, industrial y cosmética.</p>
                <p>Nuestro propósito es contribuir a que los productos de nuestros clientes lleguen al consumidor de manera segura, confiable y con la calidad que esperan.</p>
                <div class="about-stats">
                    <div class="stat"><strong>+15</strong><span>años de experiencia</span></div>
                    <div class="stat"><strong>100%</strong><span>compromiso con la calidad</span></div>
                </div>
            </div>
            <div class="about-visual">
                <div class="about-image"><img src="<?= base_url('assets/img/about-empresa.jpg') ?>" alt="Materiales y componentes de empaque Technoliner"></div>
            </div>
        </div>
    </div>
</section>

<!-- ===== PRODUCTOS ===== -->
<section class="section section-alt" id="productos">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Productos</span>
            <h2>Productos destacados</h2>
            <p class="section-sub">Conoce algunas de nuestras soluciones de empaque y cierre.</p>
        </div>
        <?php if (empty($productos)): ?>
            <p style="text-align:center;color:var(--muted);">Estamos preparando nuestro catálogo. Vuelve pronto.</p>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($productos as $producto): ?>
                    <article class="product-card">
                        <?php if (! empty($producto['imagen_principal_ruta'])): ?>
                            <img class="product-img" src="<?= base_url('uploads/productos/' . $producto['imagen_principal_ruta']) ?>" alt="<?= esc($producto['imagen_principal_alt'] ?? $producto['nombre']) ?>" style="width:100%;height:180px;object-fit:cover;">
                        <?php else: ?>
                            <div class="product-img product-img-1">📦</div>
                        <?php endif; ?>
                        <div class="product-body">
                            <h3><?= esc($producto['nombre']) ?></h3>
                            <?php if (! empty($producto['resumen'])): ?>
                                <p><?= esc($producto['resumen']) ?></p>
                            <?php endif; ?>
                            <a href="<?= site_url('productos/' . $producto['slug']) ?>" class="link-arrow">Ver detalles →</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <p style="text-align:center;margin-top:2rem;">
                <a href="<?= site_url('productos') ?>" class="btn btn-outline">Ver catálogo completo</a>
            </p>
        <?php endif; ?>
    </div>
</section>

<!-- ===== DIFERENCIALES ===== -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Diferenciales</span>
            <h2>Lo que nos hace diferentes</h2>
            <p class="section-sub">Combinamos calidad, servicio, conocimiento técnico y capacidad de adaptación.</p>
        </div>
        <div class="diferenciales-grid">
            <article class="benefit-card">
                <div class="benefit-icon">🔄</div>
                <h3>Flexibilidad</h3>
                <p>Desarrollamos y ofrecemos soluciones según las necesidades específicas de cada cliente.</p>
            </article>
            <article class="benefit-card">
                <div class="benefit-icon">🧠</div>
                <h3>Conocimiento técnico</h3>
                <p>Entendemos a fondo los tipos de liners, materiales, aplicaciones y procesos de sellado.</p>
            </article>
            <article class="benefit-card">
                <div class="benefit-icon">🤝</div>
                <h3>Atención personalizada</h3>
                <p>Acompañamiento cercano antes, durante y después de la venta.</p>
            </article>
            <article class="benefit-card">
                <div class="benefit-icon">⚡</div>
                <h3>Capacidad de respuesta</h3>
                <p>Buscamos soluciones oportunas frente a requerimientos o inconvenientes.</p>
            </article>
            <article class="benefit-card">
                <div class="benefit-icon">🎯</div>
                <h3>Calidad y consistencia</h3>
                <p>Nos enfocamos en entregar productos que cumplan las especificaciones requeridas.</p>
            </article>
            <article class="benefit-card">
                <div class="benefit-icon">🔗</div>
                <h3>Relación a largo plazo</h3>
                <p>Buscamos ser un aliado estratégico y no simplemente un proveedor.</p>
            </article>
            <article class="benefit-card">
                <div class="benefit-icon">🏆</div>
                <h3>Experiencia en el sector</h3>
                <p>Conocimiento acumulado que nos permite entender mejor los retos de nuestros clientes.</p>
            </article>
        </div>
    </div>
</section>

<!-- ===== CALIDAD ===== -->
<section class="section section-alt" id="calidad">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Calidad</span>
            <h2>Por qué el mercado elige Technoliner</h2>
        </div>
        <div class="quality-content">
            <p>Nuestros productos hacen parte de un componente fundamental del envase: el sistema de sellado. Un liner adecuado contribuye a evitar fugas, contaminación y deterioro durante el almacenamiento y el transporte. Por eso la decisión no debe basarse únicamente en el precio, sino en la confiabilidad que aporta al producto terminado.</p>
            <p>Ofrecemos productos con calidad, desempeño y consistencia, adaptados a diferentes tipos de envases y aplicaciones, ayudando a nuestros clientes a reducir riesgos y proteger el producto que finalmente llega al consumidor.</p>
        </div>
        <div class="quality-highlight">
            <span class="quality-highlight-icon">⏱️</span>
            <p>Nos caracterizamos por nuestros <strong>tiempos de entrega</strong></p>
        </div>
    </div>
</section>

<!-- ===== PROCESOS ===== -->
<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Procesos</span>
            <h2>Cómo trabajamos</h2>
            <p class="section-sub">Conocimiento técnico, control de procesos y cercanía con el cliente en cada paso.</p>
        </div>
        <div class="process-grid">
            <article class="process-step">
                <div class="process-number">1</div>
                <h3>Entendemos tu necesidad</h3>
                <p>Analizamos la aplicación y los requerimientos de tu producto para identificar la solución más adecuada.</p>
            </article>
            <article class="process-step">
                <div class="process-number">2</div>
                <h3>Seleccionamos o desarrollamos</h3>
                <p>Definimos el liner adecuado según el material, el sistema de sellado y la aplicación específica.</p>
            </article>
            <article class="process-step">
                <div class="process-number">3</div>
                <h3>Producción controlada y trazable</h3>
                <p>Trabajamos bajo procesos definidos que garantizan uniformidad, calidad y trazabilidad del producto.</p>
            </article>
            <article class="process-step">
                <div class="process-number">4</div>
                <h3>Acompañamiento continuo</h3>
                <p>No limitamos la relación a la entrega: identificamos problemas, analizamos causas y buscamos soluciones junto a ti.</p>
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
                <p class="quote">“Una empresa muy responsable y cumplida con sus pedidos. Se destacan por entregar a tiempo, brindar una excelente atención y mantener siempre el compromiso con sus clientes. Hemos tenido una muy buena experiencia trabajando con ellos y los recomendamos por su seriedad, calidad y cumplimiento.”</p>
                <div class="testimonial-author">
                    <span class="avatar">Y</span>
                    <div><strong>Yarledy Capera</strong><small>Asistente de Gerencia</small></div>
                </div>
            </article>
            <article class="testimonial-card">
                <p class="quote">“Quiero expresar mi más sincero agradecimiento y felicitación a todo el equipo de Technoliner. Porque durante estos 5 años de relación comercial, han cumplido rigurosamente con los plazos, calidad y servicio. Su comunicación fluida y capacidad de respuesta los convierten en un aliado estratégico clave para nuestra operación.”</p>
                <div class="testimonial-author">
                    <span class="avatar">A</span>
                    <div><strong>Andrea Vargas</strong><small>Director de Operaciones</small></div>
                </div>
            </article>
            <article class="testimonial-card">
                <p class="quote">“Destacamos a Technoliner por su excelente nivel de servicio, calidad y oportunidad en las entregas. Su equipo comercial se caracteriza por su amabilidad, disposición y atención oportuna, convirtiéndolos en un aliado confiable para nuestra organización.”</p>
                <div class="testimonial-author">
                    <span class="avatar">D</span>
                    <div><strong>Dora Luz Sánchez R.</strong><small>Jefe de Compras y Comercio Exterior</small></div>
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

        <form class="contact-form" action="<?= site_url('contacto') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="form_rendered_at" value="<?= time() ?>">
            <input type="hidden" name="origen_url" value="<?= current_url() ?>">

            <?php if (session()->getFlashdata('contacto_mensaje')): ?>
                <div class="alert alert-success" style="padding:12px 16px;border-radius:8px;background:#e6f5f0;color:#0a6b55;margin-bottom:16px;">
                    <?= esc(session()->getFlashdata('contacto_mensaje')) ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('contacto_error')): ?>
                <div class="alert alert-error" style="padding:12px 16px;border-radius:8px;background:#fdecea;color:#c0392b;margin-bottom:16px;">
                    <?= esc(session()->getFlashdata('contacto_error')) ?>
                </div>
            <?php endif; ?>
            <?php $erroresContacto = session()->getFlashdata('contacto_errors') ?? []; ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="nombre">Nombre completo</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required value="<?= esc(old('nombre')) ?>">
                    <?php if (isset($erroresContacto['nombre'])): ?><small style="color:#c0392b;"><?= esc($erroresContacto['nombre']) ?></small><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" placeholder="correo@empresa.com" required value="<?= esc(old('correo')) ?>">
                    <?php if (isset($erroresContacto['correo'])): ?><small style="color:#c0392b;"><?= esc($erroresContacto['correo']) ?></small><?php endif; ?>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="telefono">Número de teléfono</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="+57 300 000 0000" value="<?= esc(old('telefono')) ?>">
                </div>
                <div class="form-group">
                    <label for="empresa">Nombre de la empresa</label>
                    <input type="text" id="empresa" name="empresa" placeholder="Tu empresa" value="<?= esc(old('empresa')) ?>">
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
                <textarea id="mensaje" name="mensaje" rows="4" placeholder="Cuéntanos en qué podemos ayudarte" required><?= esc(old('mensaje')) ?></textarea>
                <?php if (isset($erroresContacto['mensaje'])): ?><small style="color:#c0392b;"><?= esc($erroresContacto['mensaje']) ?></small><?php endif; ?>
            </div>
            <label class="form-check">
                <input type="checkbox" name="politica" required>
                <span>Acepto la <a href="<?= site_url('politica-tratamiento-datos') ?>" target="_blank" rel="noopener">política de tratamiento de datos personales</a>.</span>
            </label>
            <button type="submit" class="btn btn-primary btn-lg btn-block">Enviar mensaje</button>
        </form>
    </div>
</section>

<?= $this->include('templates/footer') ?>
