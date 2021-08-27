<?php
require_once(ROOT_PATH .'Controllers/LittlehandController.php');
require_once(ROOT_PATH .'Controllers/DynamicProperty.php');
require_once(ROOT_PATH .'Views/common/function.php');
$littlehand = new LittlehandController();
$littlehand->initModels();
$selectResults = $littlehand->selectHand();
$selectCountResults = $littlehand->selectHandsCount();
$array = array(
                "result" => $selectResults->result, 
                "rowsCount" => $selectResults->rowsCount, 
                "rows" => $selectResults->rows, 
                "id" => $selectResults->id, 
                "test_sql" => $selectResults->test_sql, 
                "test_sqlParams" => $selectResults->test_sqlParams, 
                "get" => $_GET,
                "selectCount" => $selectCountResults->rowsCount
            );
header("Content-Type: text/javascript; charset=utf-8");
echo json_encode($array);
?>