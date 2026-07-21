<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <button class="btn-toggle" id="btnToggle" aria-label="Expandir menú">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
        </button>
        <span class="logo-text"><a href="/public/index.php">Biblioflex</a></span>
    </div>

    <nav>
        <ul>
            <li>
                <a href="/public/index.php" class="nav-item">
                    <span class="icon">🏠</span>
                    <span class="text">Inicio</span>
                </a>
            </li>

            <?php if(estaLogueado()) : ?>
                <?php if(esAdmin()) : ?>
                    <li>
                        <a href="/admin/index.php" class="nav-item admin-link">
                            <span class="icon">⚙️</span>
                            <span class="text">Administración</span>
                        </a>
                    </li>
                <?php endif; ?>

                <li>
                    <a href="/public/prestamos/index.php" class="nav-item">
                        <span class="icon">📚</span>
                        <span class="text">Mis Préstamos</span>
                    </a>
                </li>
                <li>
                    <a href="/public/logout.php" class="nav-item btn-logout">
                        <span class="icon">🚪</span>
                        <span class="text">Cerrar sesión</span>
                    </a>
                </li>
            <?php else : ?>
                <li>
                    <a href="/public/login.php" class="nav-item">
                        <span class="icon">🔑</span>
                        <span class="text">Iniciar sesión</span>
                    </a>
                </li>
                <li>
                    <a href="/public/register.php" class="nav-item">
                        <span class="icon">📝</span>
                        <span class="text">Registrarse</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</aside>