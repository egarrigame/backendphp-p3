<div class="text-center mb-4">
    <h1 class="auth-logo">🛠️ ReparaYa</h1>
</div>

<div class="card p-4">

    <h3 class="mb-4 text-center">Iniciar sesión</h3>

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

    <form method="POST" action="/login">

        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control"
                   placeholder="ejemplo@email.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control"
                   placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Entrar
        </button>

    </form>

    <div class="text-center mt-3">
        <small>¿No tienes cuenta?</small><br>
        <a href="/register">Crear cuenta</a>
    </div>

</div>