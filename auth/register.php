<?php
session_start();
include "../services/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $nama = trim($_POST["nama"]);
    $nomer_hp = trim($_POST["nomer_hp"]);
    $password = trim($_POST["password"]);

    // Validasi input kosong
    if (empty($email) || empty($nama) || empty($nomer_hp) || empty($password)) {
        $error = "Semua kolom wajib diisi!";
    } else {
        // Cek apakah email sudah digunakan
        $checkQuery = $conn->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
        $checkQuery->bind_param("s", $email);
        $checkQuery->execute();
        $result = $checkQuery->get_result();

        if ($result->num_rows > 0) {
            $error = "Email sudah digunakan!";
        } else {
            $username = strtolower(str_replace(' ', '', $nama)) . rand(100, 999);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO user (nama, nomer_hp, username, email, password, role) VALUES (?, ?, ?, ?, ?, 'user')");
            $stmt->bind_param("sssss", $nama, $nomer_hp, $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $success = "Pendaftaran berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan saat menyimpan data: " . $conn->error;
            }

            $stmt->close();
        }

        $checkQuery->close();
    }
}
$path = "C:/xampp/htdocs/laporan/images/logo.jpeg";
$base64 = '';
if (file_exists($path)) {
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <link rel="icon" href="../images/login_register.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body style="font-family: Poppins, sans-serif">
    <main>
        <div class="container-fluid mt-4">
            <div class="container d-flex justify-content-center">
                <div class="card shadow-lg" style="max-width: 450px; width: 100%;">
                    <div class="card-body p-6 rounded">
                        <div class="text-center">
                            <img src="<?php echo $base64; ?>" alt="logo" style="max-width 170px; max-height: 170px;">
                            <h3 class="fw-bold">REGISTER</h3>
                            <p>Sistem Monitoring Aplikasi & Pencatatatan TIK</p>
                        </div>
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php elseif (isset($success)) : ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="masuk-tab" href="login.php" role="tab">Masuk</a>
                            </li>

                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="masuk-tab" href="register.php" role="tab">Daftar</a>
                            </li>
                        </ul>
                        <form method="post" action="register.php">
                            <div class="mb-3 text-start">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="nama@email.com" required>
                            </div>
                            <div class="mb-3 text-start">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="nama" name="nama" class="form-control" id="nama" placeholder="nama kamu" required>
                            </div>
                            <div class="mb-3 text-start">
                                <label for="nomer_hp" class="form-label">Nomer Telepon</label>
                                <input type="nomer_hp" name="nomer_hp" class="form-control" id="nomer_hp" placeholder="Nomer Hp" required>
                            </div>
                            <div class="mb-3 text-start">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="masukkan password">
                            </div>
                            <button type="submit" id="submitBtn" class="btn btn-primary w-100">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>