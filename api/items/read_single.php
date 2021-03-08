<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once "../../config/Database.php";
include_once "../../models/Placis_item.php";

$database = new Database();
$db = $database->connect();

$item = new Item($db);

$item->id = isset($_GET["id"]) ? $_GET["id"] : die();

$item->readSingleItem();

$single_item_array = array(
    "id" => $item->id,
    "title" => $item->title,
    "description" => $item->description,
    "available" => $item->available,
    "category_id" => $item->category_id,
    "category_title" => $item->category_title
);

print_r(json_encode($single_item_array));
