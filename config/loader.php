<?
use Phalcon\Loader;
/**
 * Registering an autoloader
 */
$loader = new Loader();

$loader
//        ->registerDirs([$config->application->modelsDir])
    ->registerClasses([
        "Category" => $config->application->modelsDir."category.php",
        "CategorySerial" => $config->application->modelsDir."category_serial.php",
        "Commercial" => $config->application->modelsDir."commercial.php",
        "Options" => $config->application->modelsDir."options.php",
        "Serial" => $config->application->modelsDir."serial.php",
        "Series" => $config->application->modelsDir."series.php",
        "Film" => $config->application->modelsDir."film.php",
        "CategoryFilm" => $config->application->modelsDir."category_film.php",
    ])
    ->register();