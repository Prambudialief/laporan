<?php
$config = include "../services/config_google.php";
$client_id = $config['client_id'];
$redirect_uri = $config['redirect_uri'];

$auth_url = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'response_type' => 'code',
    'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
    'access_type' => 'offline',
    'prompt' => 'select_account'
]);

header("Location: $auth_url");
exit;
