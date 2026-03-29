@extends('dashboard.app.dashboard')

@section('content')
<div x-data="{ notas: [] }">

    <!-- Ahora añadimos 'titulo' al objeto que creamos -->
    <button @click="notas.push({ titulo: 'Nueva Nota', texto: '' })">
        <iconify-icon icon="lucide:plus"></iconify-icon>
        Crear nueva nota
    </button>

    <hr>

    <template x-for="(nota, index) in notas" :key="index">
        <div style="margin-bottom: 20px; border: 1px solid #ccc; padding: 15px; border-radius: 8px;">

            <!-- Campo para el Título Editable -->
            <div style="margin-bottom: 10px;">
                <input
                    type="text"
                    x-model="nota.titulo"
                    style="font-size: 1.2rem; font-weight: bold; width: 100%; border: none; outline: none; background: transparent;"
                    placeholder="Título de la nota...">
            </div>

            <!-- Campo para el Texto -->
            <textarea
                x-model="nota.texto"
                style="width: 100%; min-height: 100px; display: block; margin-bottom: 10px;"
                placeholder="Escribe tu nota aquí..."></textarea>

            <button @click="notas.splice(index, 1)" style="color: red; cursor: pointer; border: none; background: none;">
                Eliminar nota
            </button>
        </div>
    </template>

    <div x-show="notas.length === 0">
        <p>No hay notas creadas.</p>
    </div>

</div>
@endsection