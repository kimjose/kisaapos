<?php

use Infinitops\Inventory\Controller\Utility;

require_once __DIR__ . "/vendor/autoload.php";

try {
    $dir = __DIR__ . "/assets/db/migrations/";

    $mysql_host = $_ENV['DB_HOST'];
    $mysql_database = $_ENV['DB_NAME'];
    $mysql_user = $_ENV['DB_USER'];
    $mysql_password = $_ENV['DB_PASSWORD'];
    # MySQL with PDO_MYSQL  
    $db = new PDO("mysql:host=$mysql_host;dbname=$mysql_database", $mysql_user, $mysql_password);

    $files = scandir($dir, SCANDIR_SORT_ASCENDING);
    
    for ($i = 2; $i<sizeof($files); $i++){
        $file = $files[$i];
        echo "\n Executing File: " . basename($file) ;
        $query = file_get_contents($dir . $file);
        
        // DB::statement($query);
        $stmt = $db->prepare($query);

        if ($stmt->execute()){
            echo "\n Execution successful. \n ";
        }else{ 
            throw new \Exception("\n Error occurred while executing file.\n");
        }
        
    }
    echo "\n Finished creating tables. \n";
} catch (\Throwable $e){
    echo "Exception: " . $e->getMessage();
    Utility::logError($e->getCode(), $e->getMessage());
}