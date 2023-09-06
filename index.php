<?php

$request_uri = $_SERVER['REQUEST_URI'];

function getRoutes($directory = 'app')
{
    $routes = [];
    $files = scandir($directory);

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $path = $directory . '/' . $file;

            if (is_dir($path)) {
                if (file_exists($path . '/index.php')) {
                    $routes['/' . $file] = $path . '/index.php';
                }
            } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
                // var_dump($file);
                $routes['/' . ($file === 'index.php' ? '' : pathinfo($file, PATHINFO_FILENAME))] = $path;
            }
        }
    }

    return $routes;
}

$allRoutes = getRoutes();

// var_dump($allRoutes);

if (isset($allRoutes[$request_uri])) {
    require_once $allRoutes[$request_uri];
} else {
    // Página no encontrada
    http_response_code(404);
    echo '404 Not Found';
}
