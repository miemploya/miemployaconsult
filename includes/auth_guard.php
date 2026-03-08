<?php
/**
 * Miemploya Consult — Auth Guard & RBAC Middleware
 */

require_once __DIR__ . '/db.php';

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current logged-in user data
 */
function currentUser() {
    if (!isLoggedIn()) return null;
    static $user = null;
    if ($user === null) {
        $user = db_row("SELECT id, name, email, phone, role, is_active FROM users WHERE id = ?", [$_SESSION['user_id']]);
    }
    return $user;
}

/**
 * Require login — redirect to login page if not authenticated
 */
function requireLogin() {
    if (!isLoggedIn()) {
        flash('error', 'Please log in to continue.');
        redirect(SITE_URL . '/auth/login.php');
    }
}

/**
 * Require a specific role or array of roles
 */
function requireRole($roles) {
    requireLogin();
    $user = currentUser();
    if (!$user) {
        redirect(SITE_URL . '/auth/login.php');
    }
    if (is_string($roles)) {
        $roles = [$roles];
    }
    if (!in_array($user['role'], $roles)) {
        flash('error', 'You do not have permission to access this page.');
        redirect(SITE_URL . '/index.php');
    }
}

/**
 * Check if current user is admin (super_admin or staff_admin)
 */
function isAdmin() {
    $user = currentUser();
    return $user && in_array($user['role'], ['super_admin', 'staff_admin']);
}

/**
 * Check if current user is super admin
 */
function isSuperAdmin() {
    $user = currentUser();
    return $user && $user['role'] === 'super_admin';
}

/**
 * Attempt user login
 */
function attemptLogin($email, $password) {
    $user = db_row("SELECT * FROM users WHERE email = ? AND is_active = 1", [$email]);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_email'] = $user['email'];
        return true;
    }
    return false;
}

/**
 * Register a new user
 */
function registerUser($name, $email, $password, $phone = null) {
    // Check if email already exists
    $exists = db_value("SELECT COUNT(*) FROM users WHERE email = ?", [$email]);
    if ($exists) {
        return ['success' => false, 'message' => 'Email address already registered.'];
    }
    
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $id = db_insert(
        "INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, 'user')",
        [$name, $email, $hashed, $phone]
    );
    
    return ['success' => true, 'user_id' => $id];
}

/**
 * Logout user
 */
function logoutUser() {
    session_unset();
    session_destroy();
}
