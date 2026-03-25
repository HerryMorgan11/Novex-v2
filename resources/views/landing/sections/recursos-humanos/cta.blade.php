<style>
    {!! file_get_contents(resource_path('css/landing/sections/recursos-humanos/cta.css')) !!}
</style>

<section class="rh-cta-section">
    <div class="rh-cta-inner">
        <p class="rh-cta-pretitle">Empieza Hoy</p>
        <h2 class="rh-cta-title">
            ¿Listo para transformar<br>la gestión de tu <span>equipo</span>?
        </h2>
        <p class="rh-cta-desc">
            Únete a cientos de empresas que ya optimizan su gestión de talento con Novex HR.
            Sin configuraciones complejas, sin costes ocultos, sin compromiso inicial.
        </p>
        <div class="rh-cta-actions">
            <a href="{{ route('register') }}" class="rh-cta-btn-primary">Comenzar Gratis</a>
            <a href="{{ route('about') }}" class="rh-cta-btn-secondary">Hablar con un experto</a>
        </div>
        <p class="rh-cta-note">Sin tarjeta de crédito &bull; Configuración en minutos &bull; Soporte incluido</p>
    </div>
</section>

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
    }, { threshold: 0.1 });

    document.querySelectorAll('.rh-func-card, .rh-metric-card, .rh-hero-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(el);
    });
});
</script>
