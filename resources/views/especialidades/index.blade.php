@extends('layouts.app')

@section('content')
    <h1>Gestion de Especialidades</h1>

    <a href="{{ route('especialidades.create') }}" class="btn btn-primary mb-3">+ Nueva Especialidad</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre de la Especialidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($especialidades as $especialidad)
            <tr>
                <td>{{ $especialidad->id }} </td>
                <td>{{ $especialidad->nombre_especialidad }} </td>
                <td>
                    <a href="{{ route('especialidades.edit', $especialidad) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('especialidades.destroy', $especialidad) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Eliminar?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection