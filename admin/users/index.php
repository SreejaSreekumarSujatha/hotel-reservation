<?php
require __DIR__ . '/../../includes/functions.php';
require __DIR__ . '/../../includes/db_connect.php';
require_admin(); // redirect if not admin

// Fetch users
$stmt = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();

// Load the HTML template
include __DIR__ . '/view.php';
