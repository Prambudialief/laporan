<nav class="sb-topnav navbar navbar-expand navbar-light bg-light border">
    <img src="../images/logo2.png" style="max-width: 40px; margin-left:15px;" alt="">
    <a style="color: black;" class="navbar-brand ps-3" href="../user/dashboard.php">SimonaCantik</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>

    <!-- Navbar Search -->
    <form class="d-none d-md-inline-block form-inline ms-auto me-3"
        action="../user/daftar_laporan.php"
        method="GET">
        <div class="input-group">
            <input
                class="form-control"
                type="text"
                name="q"
                placeholder="Cari laporan..."
                value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>


    <!-- Navbar User -->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" data-bs-toggle="dropdown"><i style="color: black;" class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item text-center" href="../user/profile.php">Profile</a></li>

                <li><a class="dropdown-item text-center" href="../auth/logout.php">Logout</a></li>
            </ul>
        </li>
    </ul>
    <!-- Modal Profile -->
</nav>