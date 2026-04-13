<div class="text-center mb-4">
    <h1 class="auth-logo">🛠️ ReparaYa</h1>
</div>

<div class="card p-4">

    <h3 class="mb-4 text-center">Crear cuenta</h3>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/register">

        <div class="mb-3">
            <label class="form-label">Nombre completo</label>
            <input type="text" name="nombre" class="form-control"
                   placeholder="Tu nombre" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control"
                   placeholder="ejemplo@email.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control"
                   placeholder="600123123" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control"
                   placeholder="Mínimo 4 caracteres" required>
        </div>

        <button type="submit" class="btn btn-success w-100">
            Crear cuenta
        </button>

    </form>

    <div class="text-center mt-3">
        <small>¿Ya tienes cuenta?</small><br>
        <a href="/login">Iniciar sesión</a>
    </div>

</div>