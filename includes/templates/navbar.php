<nav>
    <h1>Biblioflex</h1>

    <ul>
        <li><a href="/public/index.php">Inicio</a></li>

        <?php if(estaLogueado()) : ?>
            <?php if(esAdmin()) : ?>
                <li><a href="../admin/index.php">Administracion</a></li>
            <?php endif; ?>

            <li><a href="../public/prestamos/index.php">Mis Préstamos</a></li>
            <li><a href="./logout.php">Cerrar sesion</a></li>
        <?php else : ?>
            <li><a href="./login.php">Iniciar sesion</a></li>
            <li><a href="./register.php">Registrarse</a></li>
        <?php endif; ?>
    </ul>
</nav>
