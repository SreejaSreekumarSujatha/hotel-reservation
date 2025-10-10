<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Escape output to prevent XSS
function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Flash messages
function flash_set($key, $message) {
    $_SESSION['flash'][$key] = $message;
}
function flash_get($key) {
    if (!empty($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

// Authentication helpers
function is_logged_in() {
    return !empty($_SESSION['user']);
}
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
function current_user_id() {
    return $_SESSION['user']['id'] ?? null;
}
