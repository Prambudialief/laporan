<?php
session_start();
require '../services/connection.php';

$role = $_SESSION['role'];

// Ambil data profil user yg sedang login
$query = mysqli_query($conn, "SELECT * FROM user WHERE role = '$role'");
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
    <link rel="icon" href="../images/logo.jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .profile-card {
            max-width: 500px;
            margin: 60px auto;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .profile-header {
            background-color: #CF0F0F;
            color: white;
            text-align: center;
            padding: 30px 20px;
        }

        .profile-header img {
            border-radius: 50%;
            max-width: 85px;
            max-height: 85px;
            background: #ffffff;
            padding: 5px;
        }

        .profile-body {
            background: #fff;
            padding: 25px;
        }

        .form-label {
            font-weight: 600;
        }

    

    </style>
</head>

<body>

    <div class="profile-card">

        <div class="profile-header">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png">
            <h3 class="mt-2 mb-0"><?= htmlspecialchars($data['nama']) ?></h3>
            <small><?=  htmlspecialchars($data['role']) ?></small>
        </div>

        <div class="profile-body">
            <form action="update_profile.php" method="POST">

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control"
                        value="<?= $data['nama'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" name="nomer_hp" class="form-control"
                        value="<?= $data['nomer_hp'] ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control"
                        value="<?= $data['username'] ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="<?= $data['email'] ?>">
                </div>

                <hr>

                <div class="mb-3">
                    <label class="form-label">Password Baru (opsional)</label>
                    <input type="password" name="password" class="form-control"
                        placeholder="Kosongkan jika tidak diganti">
                </div>

                <button class="btn btn-danger w-100 mt-2">Update Profil</button>
                <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Batal</a>

            </form>
        </div>

    </div>

</body>

</html>