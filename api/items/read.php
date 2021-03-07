<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include_once "../../config/Database.php";
include_once "../../models/Placis_item.php";

// Instantiate DB and connect.
$database = new Database();
$db = $database->connect();

// Instantiate item.
$item = new Item($db);
$result = $item->readItems();

$row_count = $result->rowCount();
echo $row_count;


if ($row_count > 0) {
    $item_array = array();
    $item_array["data"] = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $item_item = array(
            "id" => $id,
            "title" => $title,
            "body" => html_entity_decode($description),
            "available" => $available,
            "category_id" => $category_id,
            "category_name" => $category_title,
            "created" => $created
        );

        array_push($item_array["data"], $item_item);
    }
    echo json_encode($item_array);
} else {
    echo json_encode(
        ["message" => "No Items Found"]
    );
}
