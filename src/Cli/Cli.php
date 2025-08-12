<?php

namespace silverorange\DevTest\Cli;

class Cli {

    protected \PDO $db;

    private $start_time;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
        $this->start_time = microtime(true);
    }

    public function run() {

    }

    protected function log($message)
    {
        $timestamp = date('Y-m-d H:i:s');
        echo " $timestamp | $message\n";
    }


    public function finish()
    {
        $end_time = microtime(true);
        $elapsed = number_format($end_time - $this->start_time, 4);
        $class_name = self::removeNamespace(get_class($this));
        echo "\n$class_name process completed in $elapsed seconds.\n";
    }


    /**
     * Convenience function to remove namespace from full class name.
     */
    public static function removeNamespace($class_name)
    {
        return substr(strrchr($class_name, '\\'), 1);
    }

}


