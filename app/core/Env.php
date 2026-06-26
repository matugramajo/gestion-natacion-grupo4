<?php

class Env
{

    public static function load($path)
    {

        if (!file_exists($path)) {
            throw new Exception(".env file not found");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            $line = trim($line);

            if (strpos($line, '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);

            $name = trim($name);
            $value = trim($value);

            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }

    public static function get($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}
