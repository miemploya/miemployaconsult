<?php
/**
 * Miemploya Consult — Global Configuration
 * Empleo System Limited
 */

// Prevent direct access
if (!defined('MIEMPLOYA_LOADED')) {
    define('MIEMPLOYA_LOADED', true);
}

// ── Database Configuration ──────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'miemployaconsult');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// ── Site Configuration ──────────────────────────────────────
define('SITE_NAME', 'Miemploya Consult');
define('SITE_TAGLINE', 'Business Consulting & Digital Solutions');

// Auto-detect URL: works on both localhost and production
$_protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
if (strpos($_host, 'localhost') !== false || strpos($_host, '127.0.0.1') !== false) {
    // Local development — include the subfolder
    define('SITE_URL', $_protocol . '://' . $_host . '/miemployaconsult');
} else {
    // Production — clean domain (https://miemploya.com)
    define('SITE_URL', $_protocol . '://' . $_host);
}

define('SITE_EMAIL', 'info@miemploya.com');
define('SITE_PHONE', '+234 800 000 0000');
define('SITE_ADDRESS', '19 Adesuwa Road, GRA, Benin City, Nigeria');

// ── Company Info ────────────────────────────────────────────
define('COMPANY_NAME', 'Empleo System Limited');
define('COMPANY_TAGLINE', 'Consulting & Technology Solutions');

// ── Upload Paths ────────────────────────────────────────────
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_CVS', UPLOAD_DIR . 'cvs/');
define('UPLOAD_TEMPLATES', UPLOAD_DIR . 'templates/');
define('UPLOAD_POSTERS', UPLOAD_DIR . 'posters/');
define('UPLOAD_NEWS', UPLOAD_DIR . 'news/');
define('UPLOAD_VIDEOS', UPLOAD_DIR . 'videos/');
define('UPLOAD_PRODUCTS', UPLOAD_DIR . 'products/');
define('UPLOAD_LOGOS', UPLOAD_DIR . 'logos/');
define('UPLOAD_BOOKS', UPLOAD_DIR . 'books/');

// ── Upload Limits ───────────────────────────────────────────
define('MAX_CV_SIZE', 5 * 1024 * 1024);       // 5MB
define('MAX_IMAGE_SIZE', 3 * 1024 * 1024);     // 3MB
define('MAX_TEMPLATE_SIZE', 10 * 1024 * 1024); // 10MB

// ── Session Config ──────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── Timezone ────────────────────────────────────────────────
date_default_timezone_set('Africa/Lagos');

// ── Helper Functions ────────────────────────────────────────
function site_url($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function flash($key, $value = null) {
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
    } else {
        $msg = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function generate_slug($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9-]/', '-', $text);
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

function format_date($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

function is_ajax() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function json_response($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
