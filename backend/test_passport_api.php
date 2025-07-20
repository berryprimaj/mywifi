<?php

function request($method, $url, $data = null, $token = null) {
    $opts = [
        'http' => [
            'method' => $method,
            'header' => "Content-Type: application/json\r\n",
            'ignore_errors' => true
        ]
    ];
    if ($data) {
        $opts['http']['content'] = json_encode($data);
    }
    if ($token) {
        $opts['http']['header'] .= "Authorization: Bearer $token\r\n";
    }
    $context = stream_context_create($opts);
    $result = file_get_contents($url, false, $context);
    $status = null;
    if (isset($http_response_header[0])) {
        preg_match('/\d{3}/', $http_response_header[0], $matches);
        $status = $matches[0] ?? null;
    }
    return [
        'status' => $status,
        'body' => $result ? json_decode($result, true) : null
    ];
}

$base = 'http://localhost:8000/api';

// 1. Register user
$register = request('POST', "$base/register", [
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => 'admin123',
    'password_confirmation' => 'admin123',
    'role' => 'admin'
]);
echo "REGISTER: ", json_encode($register), "\n\n";

// 2. Login user
$login = request('POST', "$base/login", [
    'email' => 'admin@example.com',
    'password' => 'admin123'
]);
echo "LOGIN: ", json_encode($login), "\n\n";

$token = $login['body']['data']['token'] ?? null;
if (!$token) {
    echo "Gagal login, tidak dapat token.\n";
    exit(1);
}

// 3. Get user
$user = request('GET', "$base/user", null, $token);
echo "GET USER: ", json_encode($user), "\n\n";

// 4. Logout
$logout = request('POST', "$base/logout", null, $token);
echo "LOGOUT: ", json_encode($logout), "\n\n"; 