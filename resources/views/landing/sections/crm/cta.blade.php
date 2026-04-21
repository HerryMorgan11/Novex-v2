@push('styles')
@vite(['resources/css/landing/sections/crm/cta.css'])
@endpush

<section class="crm-cta-section">
    <div class="crm-cta-inner">
        <p class="crm-cta-pretitle">Empieza Hoy</p>
        <h2 class="crm-cta-title">
            ¿Tu equipo de ventas listo<br>para cerrar <span>más negocios</span>?
        </h2>
        <p class="crm-cta-desc">
            Únete a equipos comerciales que ya transformaron su proceso de ventas con Novex CRM.
            Sin curva de aprendizaje, sin importaciones complicadas, sin sorpresas.
        </p>
        <div class="crm-cta-actions">
            <a href="{{ route('register') }}" class="crm-cta-btn-primary">Comenzar Gratis</a>
            <a href="{{ route('about') }}" class="crm-cta-btn-secondary">Hablar con ventas</a>
        </div>
        <p class="crm-cta-note">Sin tarjeta de crédito &bull; Importa tus contactos en minutos &bull; Soporte incluido</p>
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

    document.querySelectorAll('.crm-func-card, .crm-metric-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(el);
    });
});
</script>
