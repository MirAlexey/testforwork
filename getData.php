<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 21.05.2017
 * Time: 22:23
 */
require("Data.php");

header('Content-type: text/plain; charset=utf-8');
header('Cache-Control: no-store, no-cache');
header('Expires: ' . date('r'));

$data = new myNs\Data();

echo json_encode($data->getData());