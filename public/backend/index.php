<?php
$path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
$normalizedPath = rtrim($path ?? '', '/');
if ($normalizedPath === '/backend/login' || $normalizedPath === '/login') {
    $login = __DIR__ . '/../dd-admin/dist/backend/pro-admin/index.html';
    if (is_file($login)) {
        header('Content-Type: text/html; charset=UTF-8');
        readfile($login);
        exit;
    } else {
        error_log('[backend-login] login file missing: '.$login);
    }
}

require __DIR__ . '/../admin/index.php';
