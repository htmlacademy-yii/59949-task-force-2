<?php

generateSQLQueryFile('categories', 'data/categories.csv', 'categoriesQuery.sql');

function generateSQLQueryFile (string $tableName, string $csvFilePath, string $sqlFileName) {
    $fileToRead = new SplFileObject($csvFilePath, "r");
    $fileToWrite = new SplFileObject($sqlFileName, "w");

    $fileToRead->rewind();

    $headerColumns = getHeaderColumns($fileToRead);

    $sqlQuery = "INSERT INTO $tableName ($headerColumns) VALUES";

    $fileToWrite->fwrite($sqlQuery);

    foreach (getFileLines($fileToRead) as $fileLine) {
        print($fileLine . '<br>');
        $fileToWrite->fwrite("\n('$fileLine'),");
    }

    terminateFileInstruction($fileToWrite);
}

function getHeaderColumns ($file): string
{
    $fileLineAsArray = $file->fgetcsv();
    return implode(', ', $fileLineAsArray);
}

function getFileLines (object $inputFile): Generator
{
    while (!$inputFile->eof()) {
        $fileLineAsArray = $inputFile->fgetcsv();
        yield implode("', '", $fileLineAsArray);
    }
}

function terminateFileInstruction(object $file):void
{
    $currentFilePosition = $file->ftell();
    $file->ftruncate($currentFilePosition - 1);
    $file->fseek($currentFilePosition - 1);
    $file->fwrite(";");
    $file->fwrite("\n");
}
