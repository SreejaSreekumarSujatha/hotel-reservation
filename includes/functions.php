<?php

if (session_status() === PHP_SESSION_NONE) session_start();

// Escape output to prevent XSS
if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

// Flash messages
if (!function_exists('flash_set')) {
    function flash_set($key, $message) {
        $_SESSION['flash'][$key] = $message;
    }
}
if (!function_exists('flash_get')) {
    function flash_get($key) {
        if (!empty($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }
        return null;
    }
}

// Authentication helpers
if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return !empty($_SESSION['user']);
    }
}
if (!function_exists('require_login')) {
    function require_login() {
        if (!is_logged_in()) {
            header('Location: login.php');
            exit;
        }
    }
}
if (!function_exists('current_user_id')) {
    function current_user_id() {
        return $_SESSION['user']['id'] ?? null;
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return !empty($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin';
    }
}
if (!function_exists('require_admin')) {
    function require_admin() {
        if (!is_admin()) {
            flash_set('error', 'Admin access required.');
            header('Location: login.php');
            exit;
        }
    }
}
