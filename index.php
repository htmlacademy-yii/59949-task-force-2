<?php

$cityHeaders = 'name, coordinates';

generateSQLQueryFile('data/categories.csv', 'categoriesQuery.sql', 'categories');
generateSQLQueryFile('data/cities.csv', 'citiesQuery.sql', 'cities', $cityHeaders, true);

function generateSQLQueryFile (
    string $csvFilePath,
    string $sqlFileName,
    string $tableName,
    ?string $customHeaders = null,
    ?bool $isPoint = false
)
{
    $fileToRead = new SplFileObject($csvFilePath, "r");
    $fileToWrite = new SplFileObject($sqlFileName, "w");

    $fileToRead->rewind();

    $headerColumns = getHeaderColumns($fileToRead);

    $headers = $customHeaders ?? $headerColumns;

    $sqlQuery = "INSERT INTO $tableName ($headers) VALUES";

    $fileToWrite->fwrite($sqlQuery);

    if ($isPoint) {
        foreach (getFileLines($fileToRead) as $fileLine) {
            $fileLine = "'$fileLine[0]', POINT($fileLine[1], $fileLine[2])";
            $fileToWrite->fwrite("\n($fileLine),");
        }
    } else {
        foreach (getFileLines($fileToRead) as $fileLine) {
            $fileLine = implode("', '", $fileLine);
            $fileToWrite->fwrite("\n('$fileLine'),");
        }
    }

    terminateFileInstruction($fileToWrite);
}

function getHeaderColumns ($file): string
{
    $fileLineAsArray = $file->fgetcsv();
    $fileLineAsString =  implode(', ', $fileLineAsArray);
    $bom = "\xef\xbb\xbf";

    return trim($fileLineAsString, $bom);
}

function getFileLines (object $inputFile): Generator
{
    while (!$inputFile->eof()) {
        yield $inputFile->fgetcsv();
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
