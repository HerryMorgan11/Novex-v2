<div class="footer-container">
    <div class="footer-elementos">
        <div class="box-logo-description">
            <img src="{{ asset('assets/logo/logo-novex-color.png') }}" height="150" width="150" alt="Novex" class="logo-footer">
            <p class="description-footer">Novex es una plataforma de gestión de proyectos que ayuda a equipos a organizar, planificar y colaborar de manera eficiente.</p>
            <div class="iconos">
                <iconify-icon icon="line-md:facebook" class="icon"></iconify-icon>
                <iconify-icon icon="line-md:twitter" class="icon"></iconify-icon>
                <iconify-icon icon="line-md:linkedin" class="icon"></iconify-icon>
            </div>
        </div>




        <div class="box-container">

        
        <div class="box-links">
            <h3 class="title-links">Producto</h3>
            <hr>
            <ul class="link-box">
                <li><a href="#productos" clascos="footer-link">Productos</a></li>
                <li><a href="#soluciones" class="footer-link">Soluciones</a></li>
                <li><a href="#precios" class="footer-link">Precios</a></li>
                <li><a href="#acerca-de" class="footer-link">Acerca de</a></li>
            </ul>

        </div>
            <div class="box-links">
            <h3 class="title-links">Enlaces</h3>
                <hr>
            <ul class="link-box">
                <li><a href="#productos" class="footer-link">Productos</a></li>
                <li><a href="#soluciones" class="footer-link">Soluciones</a></li>
                <li><a href="#precios" class="footer-link">Precios</a></li>
                <li><a href="#acerca-de" class="footer-link">Acerca de</a></li>
            </ul>
        </div>
        <div class="box-links">
            <h3 class="title-links">Enlaces</h3>
            <hr>
            <ul class="link-box">
                <li><a href="#productos" class="footer-link">Productos</a></li>
                <li><a href="#soluciones" class="footer-link">Soluciones</a></li>
                <li><a href="#precios" class="footer-link">Precios</a></li>
                <li><a href="#acerca-de" class="footer-link">Acerca de</a></li>
            </ul>
        </div>
    </div>
    </div>

    <hr class="hr-footer">
    <div class="footer-info">
        <p class="info-footer">© 2024 Novex. Todos los derechos reservados.</p>
        <div class="footer-legal">
            <button onclick="themeToggle()" class="theme-toggle"><iconify-icon icon="mynaui:sun"></iconify-icon></button>
            <a href="#" class="legal-link">Política de Privacidad</a>
            <a href="#" class="legal-link">Términos de Servicio</a>
        </div>
        
    </div>
</div>

<script>
    function themeToggle() {
        const body = document.body;
        body.classList.toggle('dark-theme');

        // Cambia el icono del botón
        const themeToggleBtn = document.querySelector('.theme-toggle iconify-icon');
        //cambiamos el icono dependiendo del tema actual
        if (body.classList.contains('dark-theme')) {
            themeToggleBtn.setAttribute('icon', 'mynaui:moon');
        } else {
            themeToggleBtn.setAttribute('icon', 'mynaui:sun');
        }
    }
</script>