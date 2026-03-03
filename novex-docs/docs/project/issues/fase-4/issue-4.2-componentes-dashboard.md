---
title: '[Fase 4.2] Componentes Compartidos del Dashboard'
labels: fase-4, dashboard, frontend, components, priority-high
assignees:
milestone: Fase 4 - Dashboard Foundation
---

## Tarea: Crear Componentes Compartidos Reutilizables

### Descripción

Crear una librería de componentes Blade y Livewire reutilizables para todo el dashboard. Estos componentes servirán como base para la construcción de todos los módulos del ERP.

### Objetivos

#### Componentes Blade Básicos

- [ ] Card component (tarjeta básica)
- [ ] Alert component (alertas y flash messages)
- [ ] Button component (botones con variantes)
- [ ] Badge component (etiquetas)
- [ ] Input group component (campos de formulario)
- [ ] Form component (formularios base)

#### Componentes Livewire Avanzados

- [ ] Modal component (modales reutilizables)
- [ ] ConfirmDelete component (confirmación de eliminación)
- [ ] Table component (tablas con paginación/búsqueda)
- [ ] Pagination component (paginación)
- [ ] Flash messages component
- [ ] Loading indicator component

#### Utilidades CSS

- [ ] Clases CSS personalizado (helpers, utilities)
- [ ] Configuración Tailwind extendida
- [ ] Tema de colores consistente

### Implementación

#### 1. Componentes Blade Básicos

##### Card Component

`resources/views/components/card.blade.php`

```blade
<div class="bg-white rounded-lg shadow {{ $class ?? '' }}">
    @isset($header)
    <div class="px-6 py-4 border-b border-gray-200">
        {{ $header }}
    </div>
    @endisset

    <div class="px-6 py-4">
        {{ $slot }}
    </div>

    @isset($footer)
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        {{ $footer }}
    </div>
    @endisset
</div>
```

**Uso:**

```blade
<x-card class="mb-4">
    <x-slot:header>
        <h3 class="text-lg font-semibold">Título</h3>
    </x-slot>

    Contenido de la tarjeta

    <x-slot:footer>
        <button>Acción</button>
    </x-slot>
</x-card>
```

##### Alert Component

`resources/views/components/alert.blade.php`

```blade
@php
$classes = match($type ?? 'info') {
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'error' => 'bg-red-50 border-red-200 text-red-800',
    'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
    'info' => 'bg-blue-50 border-blue-200 text-blue-800',
};
@endphp

<div class="border rounded-lg p-4 {{ $classes }}">
    <div class="flex items-start">
        @if($type === 'success')
            <svg class="h-5 w-5 text-green-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
        @endif

        <div>
            @if($title ?? null)
                <h3 class="font-medium">{{ $title }}</h3>
            @endif
            <div class="text-sm mt-1">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
```

**Uso:**

```blade
<x-alert type="success" title="¡Éxito!">
    El registro se guardó correctamente
</x-alert>
```

##### Button Component

`resources/views/components/button.blade.php`

```blade
@php
$classes = match($variant ?? 'primary') {
    'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white',
    'secondary' => 'bg-gray-200 hover:bg-gray-300 text-gray-800',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white',
    'success' => 'bg-green-600 hover:bg-green-700 text-white',
};

$sizeClasses = match($size ?? 'md') {
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-base',
    'lg' => 'px-6 py-3 text-lg',
};
@endphp

<button class="font-medium rounded-md transition-colors {{ $sizeClasses }} {{ $classes }} {{ $class ?? '' }}">
    {{ $slot }}
</button>
```

**Uso:**

```blade
<x-button variant="primary" size="lg">
    Guardar cambios
</x-button>
```

##### Badge Component

`resources/views/components/badge.blade.php`

```blade
@php
$classes = match($type ?? 'gray') {
    'gray' => 'bg-gray-100 text-gray-800',
    'blue' => 'bg-blue-100 text-blue-800',
    'red' => 'bg-red-100 text-red-800',
    'green' => 'bg-green-100 text-green-800',
    'yellow' => 'bg-yellow-100 text-yellow-800',
};
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ $slot }}
</span>
```

**Uso:**

```blade
<x-badge type="green">Activo</x-badge>
```

#### 2. Componentes Livewire Avanzados

##### Modal Component

`app/Http/Livewire/Modal.php`

```php
<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Modal extends Component
{
    public bool $isOpen = false;
    public string $title = '';
    public ?string $content = null;
    public array $buttons = [];

    public function openModal(string $title, ?string $content = null, array $buttons = []): void
    {
        $this->title = $title;
        $this->content = $content;
        $this->buttons = $buttons;
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->reset(['title', 'content', 'buttons']);
    }

    public function render()
    {
        return view('livewire.modal');
    }
}
```

`resources/views/livewire/modal.blade.php`

```blade
<div>
    @if($isOpen)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold">{{ $title }}</h2>
                </div>

                <div class="px-6 py-4">
                    {{ $content }}
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button wire:click="closeModal()" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                        Cancelar
                    </button>
                    @foreach($buttons as $button)
                        <button wire:click="{{ $button['action'] ?? '' }}"
                                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            {{ $button['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
```

##### ConfirmDelete Component

`app/Http/Livewire/ConfirmDelete.php`

```php
<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ConfirmDelete extends Component
{
    public bool $isOpen = false;
    public string $confirmText = '¿Estás seguro?';
    public string $itemName = '';
    public ?int $itemId = null;
    public string $actionMethod = '';

    public function openConfirm(string $itemName, int $itemId, string $actionMethod): void
    {
        $this->itemName = $itemName;
        $this->itemId = $itemId;
        $this->actionMethod = $actionMethod;
        $this->isOpen = true;
    }

    public function confirm(): void
    {
        $this->emit('delete', $this->itemId);
        $this->closeConfirm();
    }

    public function closeConfirm(): void
    {
        $this->isOpen = false;
        $this->reset();
    }

    public function render()
    {
        return view('livewire.confirm-delete');
    }
}
```

`resources/views/livewire/confirm-delete.blade.php`

```blade
<div>
    @if($isOpen)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-sm w-full mx-4">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 text-center">
                        {{ $confirmText }}
                    </h3>
                    <p class="mt-2 text-sm text-gray-500 text-center">
                        ¿Deseas eliminar "<strong>{{ $itemName }}</strong>"?
                    </p>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button wire:click="closeConfirm()" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">
                        Cancelar
                    </button>
                    <button wire:click="confirm()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
```

### Criterios de Aceptación

- [ ] Todos los componentes Blade creados y funcionales
- [ ] Todos los componentes Livewire creados y probados
- [ ] Documentación de uso para cada componente
- [ ] Ejemplos de implementación en vistas
- [ ] Estilos consistentes en todos los componentes
- [ ] Componentes responsive y accesibles
- [ ] Tests unitarios para componentes Livewire

### Testing

```php
// Tests/Feature/Components/ButtonComponentTest.php
public function test_button_renders_correctly()
{
    $view = $this->blade('<x-button>Guardar</x-button>');
    $this->assertStringContainsString('Guardar', $view);
}

// Tests/Feature/Livewire/ModalTest.php
public function test_modal_opens_and_closes()
{
    $component = Livewire::test(Modal::class);

    $component->call('openModal', 'Test Title', 'Test Content');
    $this->assertTrue($component->get('isOpen'));

    $component->call('closeModal');
    $this->assertFalse($component->get('isOpen'));
}
```

### Dependencias

- ✅ Fase 4.1: Layout Dashboard debe estar completo

### Notas Importantes

1. **Reutilización**: Diseñar componentes para máxima reutilización
2. **Props Consistentes**: Usar props consistentes entre componentes
3. **Variantes**: Soportar múltiples variantes (colores, tamaños)
4. **Accesibilidad**: Asegurar que los componentes sean accesibles (ARIA)
5. **Performance**: Los componentes Livewire deben ser eficientes

---

**Estimación:** 2-3 días  
**Prioridad:** Alta  
**Última actualización:** 2026-02-11
