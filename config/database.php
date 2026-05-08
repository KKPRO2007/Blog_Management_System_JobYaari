<?php

$env = static function (string $key, string $default = ''): string {
    $value = getenv($key);

    return $value === false ? $default : $value;
};

$isRender = $env('RENDER', '') === 'true';

$defaultHost = $isRender ? 'mysql' : '127.0.0.1';
$defaultPort = '3306';
$defaultDatabase = 'blog_management_system';
$defaultUser = $isRender ? 'blog_user' : 'root';
$defaultPassword = $isRender ? 'blog_password' : '';
$defaultCharset = 'utf8mb4';

return [
    'host' => $env('DB_HOST', $defaultHost),
    'port' => $env('DB_PORT', $defaultPort),
    'database' => $env('DB_NAME', $defaultDatabase),
    'username' => $env('DB_USER', $defaultUser),
    'password' => $env('DB_PASS', $defaultPassword),
    'charset' => $env('DB_CHARSET', $defaultCharset),
];
