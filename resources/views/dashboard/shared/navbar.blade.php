<nav class="navbar">
    @php
        $routeName = Route::currentRouteName();

        $breadcrumbs = [
            'dashboard' => [['label' => 'Dashboard', 'url' => route('dashboard')]],
            'controlpanel.home' => [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Panel de control', 'url' => route('controlpanel.home')], // actual
            ],

            'settings.profile' => [
                ['label' => 'Dashboard', 'url' => route('dashboard')],
                ['label' => 'Ajustes', 'url' => route('settings.profile')], // actual
            ],
        ];
    @endphp

    <div class="navbar-left">
        <button class="mobile-toggle icon-btn" data-sidebar-toggle aria-label="Abrir menú">
            <iconify-icon icon="lucide:menu" width="20"></iconify-icon>
        </button>
        @if (isset($breadcrumbs[$routeName]))
            <nav aria-label="breadcrumb">
                @foreach ($breadcrumbs[$routeName] as $item)
                    @if (!$loop->last && $item['url'])
                        <a class="breadcrumb-item" href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                    @else
                        <span class="breadcrumb-item active">{{ $item['label'] }}</span>
                    @endif
                @endforeach
            </nav>
        @endif
    </div>

    <div class="navbar-right">
        <button class="theme-toggle icon-btn" type="button" onclick="themeToggle()" aria-label="Cambiar tema">
            <iconify-icon icon="mynaui:sun" width="16"></iconify-icon>
        </button>

        <div class="profile-menu">
            <button class="user-btn" aria-haspopup="true" aria-expanded="false">
                <span class="avatar">JD</span>
            </button>
           
        </div>
    </div>
</nav>


<script>
    function themeToggle() {
        const html = document.documentElement;
        html.classList.toggle('dark-theme');

        const isDark = html.classList.contains('dark-theme');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');

        updateThemeIcons(isDark);
    }

    function updateThemeIcons(isDark) {
        const icons = document.querySelectorAll('.theme-toggle iconify-icon');
        icons.forEach(icon => {
            if (icon && icon.setAttribute) {
                icon.setAttribute('icon', isDark ? 'mynaui:moon' : 'mynaui:sun');
            }
        });
    }

    // Sync icons on page load
    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark-theme');
        updateThemeIcons(isDark);
    });
</script>
