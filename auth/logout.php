<?php
/**
 * Miemploya Consult — Logout Handler
 */
require_once __DIR__ . '/../includes/auth_guard.php';

logoutUser();
redirect(SITE_URL . '/auth/login.php');
