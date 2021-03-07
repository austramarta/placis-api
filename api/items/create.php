<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

include_once "../../config/Database.php";
include_once "../../models/Placis_item.php";

$database = new Database();
$db = $database->connect();

$item = new Item($db);

$data = json_decode(file_get_contents("php://input"));

$item->title = $data->title;
$item->description = $data->description;
$item->available = $data->available;
$item->category_id = $data->category_id;


//create the item
if ($item->createItem()) {
    echo json_encode(
        array("message" => "item Created")
    );
} else {
    echo json_encode(
        array("message" => "item Not Created")
    );
}
