<?php

namespace Infinitops\Inventory\Controllers;

class BaseController
{
    public function __construct()
    {
        header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
        header("Access-Control-Allow-Origin: http://localhost:3000"); // Allows communication from this url
        // header("Access-Control-Allow-Credentials: true");
        // header("Access-Control-Max-Age: 1000");
        // header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    }

    public static function logError($code, $message)
    {
        if (!is_dir($_ENV['LOGS_DIR'])) {
            mkdir($_ENV['LOGS_DIR']);
        }
        $handle = fopen($_ENV['LOGS_DIR'] . "errors.txt", 'a');
        $data = date("Y-m-d H:i:s ", time());
        $data .= "      Code " . $code;
        $data .= "      Message " . $message;
        $data .= "      ClientAddr " . $_SERVER["REMOTE_ADDR"];
        $data .= "\n";
        fwrite($handle, $data);
        fclose($handle);
    }

    public function response($code, $message, $data = null)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            "code" => $code,
            "message" => $message,
            "data" => $data
        ]);
    }
}
