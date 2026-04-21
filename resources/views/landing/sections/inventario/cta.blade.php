@push('styles')
@vite(['resources/css/landing/sections/inventario/cta.css'])
@endpush

<section class="inv-cta-section">
    <div class="inv-cta-inner">
        <p class="inv-cta-pretitle">Empieza Hoy</p>
        <h2 class="inv-cta-title">
            ¿Listo para tener el<br>control total de tu <span>stock</span>?
        </h2>
        <p class="inv-cta-desc">
            Únete a empresas que ya eliminaron el desabastecimiento, redujeron costes
            logísticos y ganaron visibilidad total sobre su cadena de suministro.
        </p>
        <div class="inv-cta-actions">
            <a href="{{ route('register') }}" class="inv-cta-btn-primary">Comenzar Gratis</a>
            <a href="{{ route('about') }}" class="inv-cta-btn-secondary">Hablar con ventas</a>
        </div>
        <p class="inv-cta-note">Sin tarjeta de crédito &bull; Configuración guiada &bull; Datos de prueba incluidos</p>
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

    document.querySelectorAll('.inv-func-card, .inv-metric-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(el);
    });
});
</script>
