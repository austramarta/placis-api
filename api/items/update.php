<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With");

require_once "../../config/Database.php";
require_once "../../models/Placis_item.php";

$database = new Database();
$db = $database->connect();

$item = new Item($db);

$data = json_decode(file_get_contents("php://input"));
$item->id = $data->id;

$item->title = $data->title;
$item->description = $data->description;
$item->available = $data->available;
$item->category_id = $data->category_id;

if ($item->updateItem()) {
    echo json_encode(
        array("message" => "Item Updated")
    );
} else {
    echo json_encode(
        array("message" => "Item Not Updated")
    );
}
