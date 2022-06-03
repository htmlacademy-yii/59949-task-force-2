<?php declare(strict_types=1);
namespace TaskForce\App\Helpers;


use Generator;
use SplFileObject;

class CsvIntoSqlConverter
{
    private string $csvFilePath;
    private string $sqlFileName;
    private string $tableName;
    private ?string $customHeaders;
    private ?bool $isPoint;

    public function __construct(
        string $csvFilePath,
        string $sqlFileName,
        string $tableName,
        ?string $customHeaders = null,
        ?bool $isPoint = false
    )
    {
        $this->csvFilePath = $csvFilePath;
        $this->sqlFileName = $sqlFileName;
        $this->tableName = $tableName;
        $this->customHeaders = $customHeaders;
        $this->isPoint = $isPoint;
    }

    public function generateFile():void
    {
        $fileToRead = new SplFileObject($this->csvFilePath, "r");
        $fileToWrite = new SplFileObject($this->sqlFileName, "w");

        $fileToRead->rewind();

        $headerColumns = $this->getFileHeaderColumns($fileToRead);

        $headers = $this->customHeaders ?? $headerColumns;

        $sqlQuery = "INSERT INTO $this->tableName ($headers) VALUES";

        $fileToWrite->fwrite($sqlQuery);

        $this->writeDataIntoFile($fileToRead, $fileToWrite);

        $this->terminateFileInstruction($fileToWrite);
    }

    private function getFileHeaderColumns($file): string
    {
        $fileLineAsArray = $file->fgetcsv();
        $fileLineAsString =  implode(', ', $fileLineAsArray);
        $bom = "\xef\xbb\xbf";

        return trim($fileLineAsString, $bom);
    }

    private function getFileLines(object $inputFile): Generator
    {
        while (!$inputFile->eof()) {
            yield $inputFile->fgetcsv();
        }
    }

    private function writeDataIntoFile(object $inputFile, object $outputFile): void
    {
        if ($this->isPoint) {
            foreach ($this->getFileLines($inputFile) as $fileLine) {
                $fileLine = "'$fileLine[0]', POINT($fileLine[1], $fileLine[2])";
                $outputFile->fwrite("\n($fileLine),");
            }
        } else {
            foreach ($this->getFileLines($inputFile) as $fileLine) {
                $fileLine = implode("', '", $fileLine);
                $outputFile->fwrite("\n('$fileLine'),");
            }
        }
    }

    private function terminateFileInstruction(object $file):void
    {
        $currentFilePosition = $file->ftell();
        $file->ftruncate($currentFilePosition - 1);
        $file->fseek($currentFilePosition - 1);
        $file->fwrite(";");
        $file->fwrite("\n");
    }
}
