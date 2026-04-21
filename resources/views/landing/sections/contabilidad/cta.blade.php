@push('styles')
@vite(['resources/css/landing/sections/contabilidad/cta.css'])
@endpush

<section class="conta2-cta-section">
    <div class="conta2-cta-inner">
        <p class="conta2-cta-pretitle">Empieza Hoy</p>
        <h2 class="conta2-cta-title">
            ¿Listo para tomar el control<br>de tus <span>finanzas</span>?
        </h2>
        <p class="conta2-cta-desc">
            Une a tu equipo financiero a la plataforma de contabilidad más completa del mercado.
            Sin configuraciones complejas, sin costes ocultos, sin complicaciones.
        </p>
        <div class="conta2-cta-actions">
            <a href="{{ route('register') }}" class="conta2-cta-btn-primary">Comenzar Gratis</a>
            <a href="{{ route('about') }}" class="conta2-cta-btn-secondary">Solicitar demo</a>
        </div>
        <p class="conta2-cta-note">Sin tarjeta de crédito &bull; Configura en minutos &bull; Soporte incluido</p>
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

    document.querySelectorAll('.conta2-func-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(el);
    });
});
</script>
