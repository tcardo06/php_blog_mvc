<?php

namespace TestProject\Engine;

class Loader
{
    private static $instance = null;

    // Make constructor private to prevent instantiation
    private function __construct() {}

    // Prevent cloning of the instance
    private function __clone() {}

    // Public method to get the single instance of the class
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Initialize the loader
    public function init()
    {
        // Register the loader method
        spl_autoload_register([$this, 'loadClasses']);
    }

    // Autoload class files
    private function loadClasses($sClass)
    {
        // Remove namespace and backslash
        $sClass = str_replace(['\\', 'TestProject'], '/', $sClass);

        // Check and require files from the current directory
        if (is_file(__DIR__ . '/' . $sClass . '.php')) {
            require_once __DIR__ . '/' . $sClass . '.php';
        }

        // Check and require files from the root path
        if (defined('ROOT_PATH') && is_file(ROOT_PATH . $sClass . '.php')) {
            require_once ROOT_PATH . $sClass . '.php';
        }
    }
}
