<?php

$tableName = 'categories';

$fileToRead = new SplFileObject("data/categories.csv", "r");
$fileToWrite = new SplFileObject("categoriesQuery.sql", "w");

$fileToRead->rewind();

$fileLineAsArray = $fileToRead->fgetcsv();
$fileLineAsString = implode(', ', $fileLineAsArray);

$fileToWrite->fwrite("INSERT INTO $tableName ($fileLineAsString) VALUES");

while (!$fileToRead->eof()) {
    $fileLineAsArray = $fileToRead->fgetcsv();
    $fileLineAsString = implode("', '", $fileLineAsArray);

    $fileToWrite->fwrite("\n('$fileLineAsString'),");
}

$currentFilePosition = $fileToWrite->ftell();
$fileToWrite->ftruncate($currentFilePosition - 1);
$fileToWrite->fseek($currentFilePosition - 1);
$fileToWrite->fwrite(";\n");
