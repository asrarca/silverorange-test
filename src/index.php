<?php

namespace silverorange\DevTest;

require __DIR__ . '/../vendor/autoload.php';

$config = new Config();
$db = (new Database($config->dsn))->getConnection();

define('APP_SRC', __DIR__);

$app = new App($db);
return $app->run(php_sapi_name() === 'cli' ? $argv : null);
