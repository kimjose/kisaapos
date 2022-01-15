<?php

namespace Infinitops\Inventory\Controllers;


use Box\Spout\Common\Entity\Style\CellAlignment;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class Utility{


    /***
     * Checks for missing attributes
     * @param array $data
     * @param array $attributes
     *
     * @return array - an array of missing attrs
     */
    public static function checkMissingAttributes(array $data, array $attributes): array
    {
        $missingAttrs = [];
        foreach ($attributes as $attribute) {
            if (!isset($data[$attribute])) array_push($missingAttrs, $attribute);
        }
        return $missingAttrs;

    }

    /***
     * Builds an excel sheet for downloading
     * @param String $name The name of the file to be built
     * @param string[] $headers The headers of the sheet
     * @param string[] $attributes The attributes contained in the data
     * @param array $data An Array containing the data to be loaded in the excel
     *
     * Note The length of these arrays i.e. $headers, $attributes should be the same
     *
     */
    public static function buildExcel($name, $headers, $attributes, $data)
    {
        try {
            if (sizeof($headers)  != sizeof($attributes))
                throw new \Exception("Invalid Data Passed", -1);

            $writer = WriterEntityFactory::createXLSXWriter();
            $writer->openToFile($name);


            $boldRowStyle = (new StyleBuilder())
                ->setFontBold()
                ->setFontSize(12)
                ->setFontUnderline()
                ->setCellAlignment(CellAlignment::CENTER)
                ->build();

            $normalRowStyle = (new StyleBuilder())
                ->setFontSize(10)
                ->setCellAlignment(CellAlignment::CENTER)
                ->build();

            $headerCells = [];
            for ($i = 0; $i < sizeof($headers); $i++){
                $header = $headers[$i];
                array_push($headerCells, WriterEntityFactory::createCell($header));
            }
            $headerRow = WriterEntityFactory::createRow($headerCells, $boldRowStyle);
            $writer->addRow($headerRow);
            foreach ($data as $datum){
                if (sizeof($datum) != sizeof($attributes))
                    throw new \Exception("Attributes mismatch. " . $i, -1);
                $datumCells = [];
                for ($i = 0; $i < sizeof($datum); $i++){
                    $m = WriterEntityFactory::createCell($datum[$attributes[$i]]);
                    array_push($datumCells, $m);
                }
                $writer->addRow(WriterEntityFactory::createRow($datumCells, $normalRowStyle));
            }
            $writer->openToBrowser($name);
            $writer->close();
            unlink($name);

        }catch (\Throwable $e){
            Utility::logError($e->getCode(), $e->getMessage());
            echo $e->getMessage();
        }
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

    public static function getAge($dob){
        $today = date("Y-m-d");
        $diff = date_diff(date_create($dob), date_create($today));
        return $diff->format('%y');
    }

    public static function uploadFiles()
    {
        try {
            if (!is_dir($_ENV['FILES_DIR'])) {
                mkdir($_ENV['FILES_DIR']);
            }
            $uploadedFiles = '';
            $count = 0;
            foreach ($_FILES['upload_files']['name'] as $file_name){
                $tmp_name = $_FILES['upload_files']['tmp_name'][$count];
                $file_name = str_replace(" ", "_", $file_name);
                $file_name = str_replace(".", "_" . time() . ".", $file_name);
                $uploaded = move_uploaded_file($tmp_name, $_ENV['FILES_DIR'] . $file_name);
                if (!$uploaded) throw new \Exception("File not uploaded");
                if($count == (sizeof($_FILES['upload_files']['tmp_name']) -1)){
                    $uploadedFiles .= $file_name;
                } else {
                    $uploadedFiles .= $file_name . ',';
                }
                $count++;
            }
            return $uploadedFiles;
        } catch (\Throwable $th) {
            self::logError($th->getCode(), $th->getMessage());
//            http_response_code(PRECONDITION_FAILED_ERROR_CODE);
            return null;
        }
    }
    
}
