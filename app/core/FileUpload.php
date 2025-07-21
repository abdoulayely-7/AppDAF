<?php

namespace app\core;

class FileUpload
{
    public static function save(array $file): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . UPLOAD_DIR;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . "." . strtolower($extension);
        $destination = $uploadDir . DIRECTORY_SEPARATOR . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        }
        return null;
    }
}
