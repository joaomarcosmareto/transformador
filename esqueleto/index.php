<?php
require __DIR__ . '/vendor/autoload.php';

// Instantiate the app
$settings = require __DIR__ . '/core/slim/settings.php';
$app = new \Slim\App($settings);
// Set up dependencies
require __DIR__ . '/core/slim/dependencies.php';
// Register routes
require __DIR__ . '/core/slim/routes.php';
// Run app
$app->run();