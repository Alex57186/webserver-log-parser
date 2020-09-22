<?php

namespace App\Console\Commands;

use App\InMemoryAggregators\Aggregator as MemoryAgg;
use App\Aggregators\DBAggregator\DBAggregator as DBAgg;
use App\Aggregators\DBAggregator\DBParserByLines;
use App\LogCliFormatter;
use Illuminate\Console\Command;

/**
 * Class ParseAccessLog
 * @package App\Console\Commands
 */
class ParseAccessLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse {filename} {--database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Displays how many times specific routes were visited, as well as how many unique users visited the routes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        $filename = $this->argument('filename');

        $filePath = storage_path().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.$filename;

        if (false === $this->validateFile($filePath)) {
            return 0;
        }

        $useDBAgrregation = $this->option('database');

        if ($useDBAgrregation) {

            $dbLogParser = new DBParserByLines($filePath,'access_logs');

            $logAggregator = new DBAgg($dbLogParser);

            $routesAndIps = $logAggregator->aggregateLogs();
            $routesAndIpsUnique = $logAggregator->aggregateLogsUniqueIps();

        } else {

            $fileContent = file_get_contents($filePath);
            $inMemoryAggregator = new MemoryAgg($fileContent);

            $routesAndIps = $inMemoryAggregator->aggregateLogs();
            $routesAndIpsUnique = $inMemoryAggregator->aggregateLogsUniqueIps();
        }

        $logCliFormatter = new LogCliFormatter();

        $str1 = $logCliFormatter->formatAsLine($routesAndIps);
        $str2 = $logCliFormatter->formatAsLine($routesAndIpsUnique, true);

        $this->info("total visits for each route");
        $this->line($str1);
        $this->info("unique visits for each route");
        $this->line($str2);

    }

    /**
     * validates that the file exist and is not empty
     * @param $filePath
     * @return bool
     */
    public function validateFile($filePath) {

        if (false === file_exists($filePath)) {
            $this->info('file does not exist');
            return false;
        }

        try {
            if (0 == $size = filesize($filePath)) {
                $this->info('file is empty');
                return false;
            }
        } catch (\Exception $e) {

            $this->info('file is empty');
            return false;
        }

        return true;
    }
}
