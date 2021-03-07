<?php

class Item
{
    private $conn;
    private $table = "items";

    public $id;
    public $title;
    public $description;
    public $available;
    public $category_id;
    public $category_title;
    public $created;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function readItems()
    {
        $query = "SELECT 
                i.id,
                i.title,
                i.description,
                i.available,
                i.category_id,
                c.title as category_title, 
                i.created
            FROM
                " . $this->table . " i
            LEFT JOIN
                categories c ON i.category_id=c.id
            ORDER BY
                i.created DESC";

        // Prepare statement and execute.
        $statement = $this->conn->prepare($query);
        $statement->execute();

        return $statement;
    }

    public function createItem()
    {
        $query = "INSERT INTO " .
            $this->table . "
            SET 
                title = :title,
                description = :description,
                available = :available,
                category_id = :category_id";


        $statement = $this->conn->prepare($query);

        // Clean data.
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->available = htmlspecialchars(strip_tags($this->available));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        $statement->bindParam(":title", $this->title);
        $statement->bindParam(":description", $this->description);
        $statement->bindParam(":available", $this->available);
        $statement->bindParam(":category_id", $this->category_id);

        if ($statement->execute()) {
            return true;
        }

        printf("Error: %s.\n, $statement->error");

        return false;
    }
}
