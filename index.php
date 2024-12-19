<?php

namespace TestProject;

use TestProject\Engine as E;
//php version display for debug or / and demo
// echo '<p style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 10px; border: 1px solid #ccc;">';
// echo 'PHP Version: ' . PHP_VERSION;
// echo '</p>';

// Start the session globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    throw new \Exception('Your PHP version is ' . PHP_VERSION . '. The script requires PHP 5.5 or higher.');
}

if (!extension_loaded('mbstring')) {
    throw new \Exception('The script requires the "mbstring" PHP extension. Please install it.');
}

// Set constants (root server path + root URL)
define('PROT', (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://');
define('ROOT_URL', PROT . $_SERVER['HTTP_HOST'] . str_replace('\\', '', dirname(htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES))) . '/'); // Remove backslashes for Windows compatibility
define('ROOT_PATH', __DIR__ . '/');

try {
    require ROOT_PATH . 'Engine/Loader.php';

    // Initialize and use the refactored Loader
    $loader = E\Loader::getInstance();
    $loader->init(); // Load necessary classes

    // Default to 'home' controller and 'index' action if no parameters are provided
    $aParams = [
        'ctrl' => (!empty($_GET['p']) ? $_GET['p'] : 'home'), // Default to 'home'
        'act' => (!empty($_GET['a']) ? $_GET['a'] : 'index')  // Default to 'index'
    ];

    E\Router::run($aParams);
} catch (\Exception $oE) {
    echo $oE->getMessage();
}
