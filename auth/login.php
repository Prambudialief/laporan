<?php
session_start();
include "../services/connection.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);


    if (empty($email) || empty($password)) {
        $error = "Email dan password wajib diisi!";
    } else {

        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();


            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["nama"] = $user["nama"];
                $_SESSION["role"] = $user["role"];


                if ($user["role"] === "admin") {
                    header("Location: ../admin/dashboard.php");
                } else {
                    header("Location: ../user/dashboard.php");
                }
                exit;
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Email tidak ditemukan!";
        }

        $stmt->close();
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
    <title>Login Page</title>
    <link rel="icon" href="../images/login_register.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body style="font-family: Poppins, sans-serif;">
    <main>
        <div class="container-fluid mt-4">
            <div class="container d-flex justify-content-center">
                <div class="card shadow-lg" style="max-width: 450px; width: 100%;">
                    <div class="card-body p-6 rounded">
                        <div class="text-center">
                            <img src="<?php echo $base64; ?>" alt="logo" style="max-width 170px; max-height: 170px;">
                            <h3 class="fw-bold">SIMONA CANTIK</h3>
                            <p>Sistem Monitoring Aplikasi & Pencatatatan TIK</p>
                        </div>
                        <!-- Alert -->
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= htmlspecialchars($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Tabs -->
                        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="masuk-tab" href="login.php" role="tab">Masuk</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="daftar-tab" href="register.php" role="tab">Daftar</a>
                            </li>
                        </ul>

                        <!-- Form -->
                        <form method="post" action="login.php">
                            <div class="mb-3 text-start">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="nama@email.com" required>
                            </div>
                            <div class="mb-3 text-start">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" required>
                            </div>
                            <button type="submit" id="submitBtn" class="btn btn-primary w-100">Login</button>
                            <h4 class="text-muted text-center mt-1">OR</h4>
                            <div class="text-center mt-3">
                                <a href="google_login.php" class="btn btn-white border border-dark w-100">
                                    <img src="https://developers.google.com/identity/images/g-logo.png" width="20"> Login with Google
                                </a>
                            </div>

                            <p class="text-center">Don't have an account? <a href="register.php">Sign up</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>