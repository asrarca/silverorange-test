<?php
namespace silverorange\DevTest;

if (!php_sapi_name() === 'cli') {
    echo "This file must be run from the command line.\n";
    die();
}

namespace silverorange\DevTest;
require __DIR__ . '/vendor/autoload.php';

$config = new Config();
$db = (new Database($config->dsn))->getConnection();

$files = glob(__DIR__ . '/data/*.json');
foreach($files as $i => $file) {
    $row = json_decode(file_get_contents($file), true);

    // create string variables so the SQL statement below is easy to read.
    $fields = implode(', ', array_keys($row));
    $placeholders = implode(', ', array_fill(0, count(array_keys($row)), '?'));

    $sql = "INSERT INTO posts ($fields) VALUES ($placeholders)";
    $statement = $db->prepare($sql);
    $statement->execute(array_values($row));
    echo "Inserted row $i\n";
}

echo "Done.\n";
