<?php

class Response {
    public static function json(
        array $data,
        int $statusCode = 200
    ):void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        json_encode($data);
    }
}