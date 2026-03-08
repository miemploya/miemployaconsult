<?php
/**
 * Miemploya Consult — Database Connection (PDO)
 */

require_once __DIR__ . '/config.php';

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            error_log("DB Connection Error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }
    }
    return $pdo;
}

/**
 * Execute a query and return all results
 */
function db_query($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Execute a query and return a single row
 */
function db_row($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

/**
 * Execute a query and return a single value
 */
function db_value($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchColumn();
}

/**
 * Execute an INSERT/UPDATE/DELETE and return affected rows
 */
function db_execute($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return $stmt->rowCount();
}

/**
 * Insert a row and return the last inserted ID
 */
function db_insert($sql, $params = []) {
    $stmt = getDB()->prepare($sql);
    $stmt->execute($params);
    return getDB()->lastInsertId();
}
