<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 24.05.2017
 * Time: 3:05
 */
require("Data.php");

header('Content-type: text/plain; charset=utf-8');
header('Cache-Control: no-store, no-cache');
header('Expires: ' . date('r'));

if(!empty($_POST['id'])) {
    $data = new myNs\Data();
    echo json_encode($data->deleteData($_POST['id']));
}else{
    echo json_encode(['result' => false,
                      'data'=> "Отсутствует ID"]);
}