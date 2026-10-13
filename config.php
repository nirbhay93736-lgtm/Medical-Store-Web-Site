<?php
declare(strict_types=1);

// Update these values for your local MySQL installation.
const DB_HOST = '127.0.0.1';
const DB_NAME = 'medistore';
const DB_USER = 'root';
const DB_PASS = '';

function db(): PDO
{
    static $pdo;
    if (!$pdo) {
        $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    return $pdo;
}

function h(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function money(float|int|string $value): string { return '₹' . number_format((float)$value, 2); }
function redirect(string $url): never { header('Location: ' . $url); exit; }
function flash(string $message, string $kind = 'success'): void { $_SESSION['flash'] = [$message, $kind]; }
function takeFlash(): ?array { $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f; }

session_start();
