<?php


namespace App\Aggregators\DBAggregator;

use Illuminate\Support\Facades\DB;

class ParserByLines implements ParserInterface
{
    private $records;
    private $filePath;
    private $tableName;

    public function getTableName(): string {
        return $this->tableName;
    }

    public function __construct(string $filePath, $tableName)
    {
        $this->tableName = $tableName;

        if (false === file_exists($filePath)) {
            throw new \Exception('file does not exist');
        }

        try {
            $size = filesize($filePath);
        } catch (\Exception $e) {
            throw new \Exception('file is empty');
        }

        $this->filePath = $filePath;
    }

    private function insertIntoDb() {

        DB::table($this->tableName)->delete();

        $valuesString = [];
        foreach ($this->records as $row) {
            $valuesString[] = sprintf("('%s', '%s')", $row['route'],$row['ip']);
        }

        $sql = "insert into {$this->tableName} (route, ip) ". ' values ' . implode(',', $valuesString);

        $pdo = DB::connection()->getPdo();
        $pdo->exec($sql);
    }

    private function parseFile() {

        $fileResource = fopen($this->filePath, 'r');

        while ($line = fgets($fileResource)) {
            $line = str_replace(array("\n", "\r"), '', $line);
            if('' == $line) {
                continue;
            }
            $recordParts = explode(' ', $line);
            $this->records[] = [
                'route' => $recordParts[0],
                'ip' => $recordParts[1]
            ];
        }

        if(empty($this->records)) {
            throw new \Exception('file is empty');
        }

        fclose($fileResource);
    }

    public function parse() {
        $this->parseFile();
        $this->insertIntoDb();
    }
}
