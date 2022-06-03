<?php declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use TaskForce\App\Helpers\CsvIntoSqlConverter;


$fileConverter = new CsvIntoSqlConverter('data/categories.csv', 'categoriesQuery_Temp.sql', 'categories');
$fileConverter->generateFile();
$fileConverter = null;

$cityHeaders = 'name, coordinates';

$fileConverter = new CsvIntoSqlConverter('data/cities.csv', 'citiesQuery_Temp.sql', 'cities', $cityHeaders, true);
$fileConverter->generateFile();
$fileConverter = null;
