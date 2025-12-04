<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<style>
    .active-menu {
        background-color: #67C0E9 !important;
        color: #fff !important;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .active-menu i {
        color: #fff !important;
    }

    .nav-link:hover {
        background-color: #67C0E9 !important;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;
        color: #fff !important;
        border-radius: 8px;
    }

    .icon-img {
        transition: filter 0.3s ease;
    }

    /* Hover nav */
    .nav-link:hover .icon-img {
        filter: brightness(0) invert(1);
    }

    /* Aktif menu */
    .active-menu .icon-img {
        filter: brightness(0) invert(1);
    }
</style>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav sb-sidenav-light">
            <div style="background-color:rgb(255, 255, 255);" class="sb-sidenav-menu border">
                <div class="nav">
                    <div class="mt-3">
                        <a class="nav-link <?php if ($current_page == 'dashboard.php') echo 'active-menu'; ?>" href="../user/dashboard.php">
                            <div class="sb-nav-link-icon"><img src="../images/Home.png" class="icon-img" style="width: 20px; height:20px;" alt=""></div>
                            DASHBOARD
                        </a>
                    </div>
                    <div class="mt-3">
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLaporan" aria-expanded="false" aria-controls="collapseLaporan">
                            <div class="sb-nav-link-icon"><img src="../images/laporan.png" class="icon-img" style="width: 20px; height:20px;" alt=""></div>
                            LAPORAN
                            <div class="sb-sidenav-collapse-arrow"><i style="color: black;" class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLaporan" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link <?php if ($current_page == 'tambah_laporan.php') echo 'active-menu'; ?>" href="../user/tambah_laporan.php"><img src="../images/laporan.png" style="width: 20px; height:20px; margin-right:5px;" class="icon-img" alt="">TAMBAH LAPORAN</a>
                                <a class="nav-link <?php if ($current_page == 'daftar_laporan.php') echo 'active-menu'; ?>" href="../user/daftar_laporan.php"><img src="../images/laporan.png" style="width: 20px; height:20px; margin-right:5px;" class="icon-img" alt="">DAFTAR LAPORAN</a>
                            </nav>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a class="nav-link <?php if ($current_page == 'rekap.php') echo 'active-menu'; ?>" href="../user/rekap.php">
                            <div class="sb-nav-link-icon"><img src="../images/rekap.png" class="icon-img" style="width: 20px; height:20px;" alt=""></div>
                            REKAPITULASI
                        </a>
                    </div>
                </div>
            </div>
            <div style="background-color: #F9F8F6" class="sb-sidenav-footer">
                <div class="small text-black">Logged in as:
                    <strong>
                        <?php echo htmlspecialchars($_SESSION['nama']) ?>
                    </strong>
                </div>
            </div>
        </nav>
    </div>

    <div id="layoutSidenav_content">
        <main>