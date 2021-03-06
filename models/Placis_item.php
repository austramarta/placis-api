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

    public function readSingleItem()
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
            WHERE 
                i.id = ?
            LIMIT 0, 1";

        $statement = $this->conn->prepare($query);
        $statement->bindParam(1, $this->id);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $this->title = $row["title"];
        $this->description = $row["description"];
        $this->available = $row["available"];
        $this->category_id = $row["category_id"];
        $this->category_title = $row["category_title"];
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

    public function updateItem()
    {
        $query = "UPDATE " .
            $this->table . "
            SET   
                title = :title,
                description = :description,
                available = :available,
                category_id = :category_id
            WHERE
                id = :id";
        $statement = $this->conn->prepare($query);

        // Clean data.
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->available = htmlspecialchars(strip_tags($this->available));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));


        $statement->bindParam(":title", $this->title);
        $statement->bindParam(":description", $this->description);
        $statement->bindParam(":available", $this->available);
        $statement->bindParam(":category_id", $this->category_id);
        $statement->bindParam(":id", $this->id);


        if ($statement->execute()) {
            return true;
        }

        printf("Error: %s.\n, $statement->error");

        return false;
    }

    public function deleteItem()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $statement = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $statement->bindParam(":id", $this->id);

        if ($statement->execute()) {
            return true;
        }
        printf("Error: %s.\n, $statement->error");
        return false;
    }
}
