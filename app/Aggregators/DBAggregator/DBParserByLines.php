<?php


namespace App\Aggregators\DBAggregator;

use Illuminate\Support\Facades\DB;

/**
 * Class ParserByLines
 * Parses log file and populates db table with this data
 * Parses file line by line
 * @package App\Aggregators\DBAggregator
 */
class DBParserByLines implements ParserInterface
{
    /**
     * @var
     */
    private $records;

    /**
     * @var string
     */
    private $filePath;

    /**
     * db table name where to insert parsed data
     * @var
     */
    private $tableName;

    /**
     * @return string db table name where to insert parsed data
     */
    public function getTableName(): string {
        return $this->tableName;
    }

    /**
     * DBParserByLines constructor.
     * @param string $filePath file to parse
     * @param $tableName where to insert data
     * @throws \Exception
     */
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

    /**
     * inserts parsed data into specified db table
     */
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

    /**
     * parses specified log file line by line
     * @throws \Exception
     */
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

    /**
     * Parses specified log file, line by line and inserts data into db table
     * @throws \Exception
     */
    public function parse() {
        $this->parseFile();
        $this->insertIntoDb();
    }
}
