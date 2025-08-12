<?php

namespace silverorange\DevTest\Cli;

class ImportPosts extends Cli {

    public function run() {
        // truncate the table to prevent duplicate entries
        // if the script is run multiple times.
        if ($this->truncateTable()) {
            $this->insertData();
        }
        return true;
    }

    private function insertData() {
        $files = glob(APP_SRC . '/../data/*.json');

        foreach ($files as $i => $file) {
            $row = json_decode(file_get_contents($file), true);

            // create string variables so the SQL statement below is easy to read.
            $fields = implode(', ', array_keys($row));
            $placeholders = implode(', ', array_fill(0, count(array_keys($row)), '?'));

            $sql = "INSERT INTO posts ($fields) VALUES ($placeholders)";
            $statement = $this->db->prepare($sql);
            $statement->execute(array_values($row));
            $this->log("Inserted row $i");
        }
    }

    /**
     * Removes all data from the posts table.
     */
    private function truncateTable() {
        try {
            $this->db->exec("TRUNCATE TABLE posts");
        } catch (PDOException $e) {
            $this->log($e->getMessage());
            return false;
        }
        return true;
    }
}


