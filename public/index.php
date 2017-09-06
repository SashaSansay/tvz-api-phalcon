<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {

    /**
     * Read the configuration
     */
    $config = include __DIR__ . '/../config/config.php';

    require __DIR__ . '/../config/services.php';

    require __DIR__ . '/../config/loader.php';

    require __DIR__ . '/../app/app.php';

    $app = new App();

    $app->url->setBaseUri($config->application->baseUri);

    $app->handle();
} catch (\Exception $e) {
    echo $e->getMessage(), PHP_EOL;
    echo $e->getTraceAsString();
}
