<?php
require "vendor/autoload.php";
if (!file_exists(__DIR__ . '/.env')){
    echo "Unable to load configurations file.";
    http_response_code(412);
    return;
}
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection([
    "driver" => $_ENV["DB_DRIVER"],
    "host" => $_ENV["DB_HOST"],
    "database" => $_ENV["DB_NAME"],
    "username" => $_ENV["DB_USER"],
    "password" => $_ENV["DB_PASSWORD"]
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();