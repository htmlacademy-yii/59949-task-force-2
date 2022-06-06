<?php declare(strict_types=1);
namespace TaskForce\App\Helpers;


use Generator;
use RuntimeException;
use SplFileObject;
use TaskForce\App\Exceptions\SourceFileException;

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

    /**
     * @throws SourceFileException
     */
    public function generateFile():void
    {
        $this->checkFileExists($this->csvFilePath);

        $fileToRead = $this->openFile($this->csvFilePath);

        $fileToWrite = $this->createFile($this->sqlFileName);

        $headerColumns = $this->getFileHeaderColumns($fileToRead);

        $headers = $this->customHeaders ?? $headerColumns;

        $this->createSQLQueryLine($headers, $fileToWrite);

        $this->writeDataIntoFile($fileToRead, $fileToWrite);

        $this->terminateFileInstruction($fileToWrite);
    }

    private function getFileHeaderColumns($file): string
    {
        $file->rewind();
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

    private function createSQLQueryLine(string $headers, object $file): void
    {
        $sqlQuery = "INSERT INTO $this->tableName ($headers) VALUES";
        $file->fwrite($sqlQuery);
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

    /**
     * @throws SourceFileException
     */
    private function checkFileExists($file): void
    {
        if (!file_exists($file)) {
            throw new SourceFileException("Файл '$file' не существует");
        }
    }

    /**
     * @throws SourceFileException
     */
    private function openFile(string $file): ?object
    {
        try {
            return new SplFileObject($file, "r");
        }
        catch (RuntimeException) {
            throw new SourceFileException("Не удалось открыть файл '$file' на чтение");
        }
    }

    private function createFile(string $fileName): object
    {
        return new SplFileObject($fileName, "w");
    }
}
