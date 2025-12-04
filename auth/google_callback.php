<?php
session_start();
include "../services/connection.php";

// === KONFIGURASI ===
$config = include "../services/config_google.php";

$client_id = $config['client_id'];
$client_secret = $config['client_secret'];
$redirect_uri = $config['redirect_uri'];

// === STEP 1: Validasi kode ===
if (!isset($_GET['code'])) {
    die("Kode otorisasi tidak ditemukan.");
}

// === STEP 2: Tukar kode dengan access token ===
$token_url = "https://oauth2.googleapis.com/token";
$data = [
    'code' => $_GET['code'],
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'redirect_uri' => $redirect_uri,
    'grant_type' => 'authorization_code',
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ],
];

$context  = stream_context_create($options);
$response = file_get_contents($token_url, false, $context);
if ($response === FALSE) {
    die('Gagal mendapatkan token dari Google.');
}

$token = json_decode($response, true);
$access_token = $token['access_token'] ?? null;

if (!$access_token) {
    die("Token tidak valid atau gagal diambil.");
}

// === STEP 3: Ambil data profil user dari Google ===
$user_info = file_get_contents("https://www.googleapis.com/oauth2/v2/userinfo?access_token=" . $access_token);
$user = json_decode($user_info, true);

// Pastikan data user valid
$email = $user['email'] ?? '';
$nama = $user['name'] ?? ''; // <-- gunakan "name" bukan "nama"
$username = explode('@', $email)[0];

if (empty($email)) {
    die("Gagal mendapatkan informasi email dari Google.");
}

// === STEP 4: Cek user di database ===
$stmt = $conn->prepare("SELECT * FROM user WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // User baru -> masukkan ke database
    $insert = $conn->prepare("INSERT INTO user (nama, username, email, password, role) VALUES (?, ?, ?, '', 'user')");
    $insert->bind_param("sss", $nama, $username, $email);
    $insert->execute();
    $user_id = $insert->insert_id;
} else {
    // User sudah ada
    $existing = $result->fetch_assoc();
    $user_id = $existing['id'];
    $nama = $existing['nama']; // ambil nama dari database (bisa disesuaikan)
}

// === STEP 5: Set session seperti login biasa ===
$_SESSION['user_id'] = $user_id;
$_SESSION['nama'] = $nama;
$_SESSION['email'] = $email;
$_SESSION['role'] = 'user';

// === STEP 6: Redirect ke dashboard ===
header("Location: ../user/dashboard.php");
exit;
?>
