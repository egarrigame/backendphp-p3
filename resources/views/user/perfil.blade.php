@extends('layouts.app')

@section('title', 'Mi Perfil - ReparaYa')

@section('content')
<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">👤 Mi perfil</h2>
    </div>

    <!-- ALERTAS -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- FORM -->
    <div class="card p-4">

        <form method="POST" action="{{ url('/producto3/perfil') }}">
            @csrf

            <div class="row">

                <!-- NOMBRE -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text"
                           name="nombre"
                           class="form-control"
                           value="{{ old('nombre', $user->nombre) }}"
                           required>
                </div>

                <!-- EMAIL -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email', $user->email) }}"
                           required>
                </div>

                <!-- TELÉFONO -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text"
                           name="telefono"
                           class="form-control"
                           value="{{ old('telefono', $user->telefono) }}"
                           required>
                </div>

                <!-- PASSWORD -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nueva contraseña</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Opcional">
                </div>

            </div>

            <!-- BOTONES -->
            <div class="d-flex justify-content-end gap-2 mt-3">

                <a href="javascript:history.back()" class="btn btn-secondary">
                    Volver
                </a>

                <button type="submit" class="btn btn-primary">
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>
@endsection
