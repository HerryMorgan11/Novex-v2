<style>
    {!! file_get_contents(resource_path('css/landing/sections/rh.css')) !!}
</style>

<div class="rh-wrapper">
    <!-- Hero Section -->
    <section class="rh-hero">
        <div class="rh-hero-content">
            <div class="rh-badge">
                <span class="dot"></span> EDITORIAL RECURSOS HUMANOS
            </div>
            <h1>
                Transforma la <span class="highlight-orange">Cultura</span> <br>
                de tu Empresa
            </h1>
            <p>
                Gestiona el talento humano, automatiza la nómina y potencia el 
                desarrollo organizacional con una plataforma diseñada para 
                arquitectos de equipos de alto impacto.
            </p>
            <div class="rh-buttons">
                <button class="btn-rh-primary" onclick="window.location.href='{{ route('register') }}'">Empieza Gratis</button>
            </div>
        </div>
        
        <div class="rh-hero-visual">
            <div class="rh-mockup-person">
                <!-- Aquí está la foto que solicitaste. Por favor, asegúrate de guardar la imagen real en public/assets/logo/rh-hero.png o actualiza la ruta abajo -->
                <img src="{{ asset('assets/logo/rh-hero.png') }}" alt="HR Hero" class="rh-hero-image" onerror="this.style.display='none'">
                <div class="rh-person" style="display: none;"></div>
                
                <div class="rh-floating-badge">
                    <div class="rh-badge-top">
                        <div class="rh-badge-icon">
                            <iconify-icon icon="fluent:data-trending-24-regular"></iconify-icon>
                        </div>
                        <div class="rh-badge-text">
                            <div class="rh-badge-title">Retención</div>
                            <div class="rh-badge-value">+24%</div>
                        </div>
                    </div>
                    <div class="rh-badge-progress">
                        <div class="rh-badge-progress-bar"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modules Section -->
    <section class="rh-modules">
        <h2>Módulos de Maestría HR</h2>
        
        <div class="rh-grid">
            <!-- Card 1 -->
            <div class="rh-card">
                <div class="rh-icon">
                    <iconify-icon icon="mdi:account-cog-outline"></iconify-icon>
                </div>
                <h3>Gestión de Talento</h3>
                <p>Optimiza tus procesos de reclutamiento y evaluación de desempeño con herramientas de análisis predictivo.</p>
            </div>
            
            <!-- Card 2 -->
            <div class="rh-card">
                <div class="rh-icon">
                    <iconify-icon icon="clarity:document-line"></iconify-icon>
                </div>
                <h3>Nómina Automatizada</h3>
                <p>Procesamiento de nómina sin errores, integrado con normativas locales y pagos automáticos programados.</p>
            </div>
            
            <!-- Card 3 -->
            <div class="rh-card">
                <div class="rh-icon">
                    <iconify-icon icon="ph:heart-bold"></iconify-icon>
                </div>
                <h3>Cultura y Bienestar</h3>
                <p>Mide el compromiso y la satisfacción del equipo mediante encuestas de pulso y programas de beneficios personalizados.</p>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="rh-testimonial">
        <div class="rh-testi-card">
            <div class="rh-quote-mark">❞</div>
            <blockquote>
                "Implementar Novex HR ha sido un punto de inflexión. No solo automatizamos la nómina, sino que recuperamos el tiempo para enfocarnos en lo que realmente importa: nuestra gente y su crecimiento."
            </blockquote>
            <div class="rh-author">
                <img src="https://ui-avatars.com/api/?name=Elena+Martinez&background=1a1a24&color=fff" alt="Elena Martinez">
                <div>
                    <span class="rh-author-name">Elena Martinez</span>
                    <span class="rh-author-role">Directora de Talento Humano, Novex ERP</span>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="rh-cta">
        <h2>¿Listo para <br> potenciar tu <br> equipo?</h2>
        <p>Únete a cientos de empresas que ya están transformando su gestión administrativa en una ventaja competitiva.</p>
        <div class="rh-cta-buttons">
            <button class="btn-cta-prim" onclick="window.location.href='{{ route('register') }}'">Comenzar ahora</button>
            <button class="btn-cta-sec">Hablar con un experto</button>
        </div>
    </section>
</div>



<script>
   document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target);
            }
        });
    });

    document.querySelectorAll('.rh-card, .rh-mockup-person, .rh-testi-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
});
</script>
