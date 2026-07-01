<?php

class AuthHelper
{
    public static function getCurrentUserId(): ?int
    {
        $headers = self::getAllHeaders();
        if (isset($headers['X-User-Id'])) {
            return (int) $headers['X-User-Id'];
        }
        if (isset($headers['x-user-id'])) {
            return (int) $headers['x-user-id'];
        }

        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    public static function getCurrentUserRole(PDO $db): string
    {
        $headers = self::getAllHeaders();
        if (isset($headers['X-User-Role'])) {
            return strtoupper($headers['X-User-Role']);
        }
        if (isset($headers['x-user-role'])) {
            return strtoupper($headers['x-user-role']);
        }

        $userId = self::getCurrentUserId();
        if ($userId !== null) {
            $stmt = $db->prepare("SELECT role FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($res && isset($res['role'])) {
                return strtoupper($res['role']);
            }
        }

        return 'BUYER';
    }

    private static function getAllHeaders(): array
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }
}
