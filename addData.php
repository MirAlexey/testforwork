<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 26.05.2017
 * Time: 14:56
 */
require("Data.php");

header('Content-type: text/plain; charset=utf-8');
header('Cache-Control: no-store, no-cache');
header('Expires: ' . date('r'));

if(!empty($_POST['data']) && !empty($_POST['number']) && !empty($_POST['param'])) {
    $data = new myNs\Data();
    echo json_encode($data->AddData($_POST['data'], $_POST['number'], $_POST['param']));
}else{
    echo json_encode(['result' => false,
        'data'=> "Неполный набор данных"]);
}