<?php declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';

use TaskForce\App\Exceptions\SourceFileException;
use TaskForce\App\Helpers\CsvIntoSqlConverter;


$fileConverter = new CsvIntoSqlConverter('data/categories.csv', 'categoriesQuery.sql', 'categories');
try {
    $fileConverter->generateFile();
}
catch (SourceFileException $e) {
    print("Не удалось обработать csv файл: " . $e->getMessage());
}
$fileConverter = null;

$cityHeaders = 'name, coordinates';
$fileConverter = new CsvIntoSqlConverter('data/cities.csv', 'citiesQuery.sql', 'cities', $cityHeaders, true);
try {
    $fileConverter->generateFile();
}
catch (SourceFileException $e) {
    print("Не удалось обработать csv файл: " . $e->getMessage());
}
$fileConverter = null;
