<?php

if (!function_exists('getConfig')) {
    function getConfig($key = null, $default = null)
    {
        static $loadedConfigs = [];

        // Ensure the key is a string, or return null if it's invalid
        if (!is_string($key) || empty($key)) {
            return $default;
        }

        // Split the key by dot notation
        $keys = explode('.', $key);

        // The first part of the key is the config file name
        $fileName = array_shift($keys);

        // Load the configuration file if not already loaded
        if (!isset($loadedConfigs[$fileName])) {
            $filePath = __DIR__ . '/../config/' . $fileName . '.php';

            if (file_exists($filePath)) {
                $loadedConfigs[$fileName] = require $filePath;
            } else {
                return $default; // Return default if the file does not exist
            }
        }

        // Get the configuration array for the file
        $config = $loadedConfigs[$fileName];

        // Traverse the nested keys to get the desired value
        foreach ($keys as $part) {
            if (is_array($config) && array_key_exists($part, $config)) {
                $config = $config[$part];
            } else {
                return $default;
            }
        }

        return $config;
    }
}
