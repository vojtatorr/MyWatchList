<?php

class WatchList
{
    private $dbConn;

    // Constructor, initializes DB connection
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    // Retrieve all show from the 'show' table
    public function getWatchList()
    {
        $stmt = $this->dbConn->prepare("SELECT * FROM shows");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve parts where id_show matches the current show's id
    public function getShowParts($id_show)
    {
        $stmt = $this->dbConn->prepare("SELECT * FROM parts WHERE id_show = :id_show");
        $stmt->bindParam(':id_show', $id_show, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    // Add a show to the 'show' table
    public function addshow($show_name, $img_dir, $show_status){
    $sql = "INSERT INTO shows (show_name, img_dir, show_status) VALUES (:show_name, :img_dir, :show_status)";
    $stmt = $this->dbConn->prepare($sql);
    $stmt->bindParam(':show_name', $show_name, PDO::PARAM_STR);
    $stmt->bindParam(':img_dir', $img_dir, PDO::PARAM_STR); // Insert file name (img_dir)
    $stmt->bindParam(':show_status', $show_status, PDO::PARAM_INT);

    return $stmt->execute();
    }

     // Add a part to the 'parts' table
     public function addPart($part_name, $id_show, $op, $ed, $num_of_ep) {
        // First, convert the number using the epNumConvert function to get the string
        $num_of_ep_string = $this->epNumConvert($num_of_ep);
        
        // Prepare the SQL query
        $sql = "INSERT INTO parts (part_name, id_show, op, ed, num_of_ep) VALUES (:part_name, :id_show, :op, :ed, :num_of_ep)";
        $stmt = $this->dbConn->prepare($sql);
        
        // Bind the parameters
        $stmt->bindParam(':part_name', $part_name, PDO::PARAM_STR);
        $stmt->bindParam(':id_show', $id_show, PDO::PARAM_INT);
        $stmt->bindParam(':op', $op, PDO::PARAM_STR);
        $stmt->bindParam(':ed', $ed, PDO::PARAM_STR);
        
        // Bind the converted num_of_ep string instead of the raw number
        $stmt->bindParam(':num_of_ep', $num_of_ep_string, PDO::PARAM_STR);
        
        // Execute the query
        return $stmt->execute();
    }

    public function filtershow($show_name)
    {
        // Základní SQL dotaz
        $sql = "SELECT * FROM shows WHERE 1=1";
        $params = [];

        // Přidání podmínek pro filtraci podle parametrů
        if (!empty($show_name)) {
            $sql .= " AND show_name LIKE :show_name";
            $params[':show_name'] = '%' . $show_name . '%';
        }
        
        // Příprava SQL dotazu
        $stmt = $this->dbConn->prepare($sql);

        // Bindování parametrů (pouze pokud byly parametry přidány)
        foreach ($params as $param => $value) {
            $stmt->bindValue($param, $value, PDO::PARAM_STR);
        }

        // Vykonání SQL dotazu
        $stmt->execute();

        // Návrat výsledků jako pole asociativních polí
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveshow() {
        $sql = "SELECT * FROM shows WHERE show_status = 2";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getToWatchshow() {
        $sql = "SELECT * FROM shows WHERE show_status = 1";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function epNumConvert($num_of_ep) {
        $number_array = [];

        for ($x = 1; $x <= $num_of_ep; $x++) {
            $number_array[] = $x;
        }
        
        $number_of_ep = implode(",", $number_array);
        return $number_of_ep;
    }

    public function editShow($show_name, $img_dir, $show_status, $id_show) {
        $sql = "UPDATE shows SET show_name = :show_name, img_dir = :img_dir, show_status = :show_status WHERE id_show = :id_show";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':show_name', $show_name, PDO::PARAM_STR);
        $stmt->bindParam(':img_dir', $img_dir, PDO::PARAM_STR);
        $stmt->bindParam(':show_status', $show_status, PDO::PARAM_INT);
        $stmt->bindParam(':id_show', $id_show, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getShow($id_show) {
        $sql = "SELECT show_name, img_dir, show_status FROM shows WHERE id_show = :id_show";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':id_show', $id_show, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteShow($id_show)
    {
        $sql = "DELETE FROM shows WHERE id_show = :id_show";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':id_show', $id_show, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deletePart($id_part) {
        $query = "DELETE FROM parts WHERE id_part = :id_part";
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':id_part', $id_part, PDO::PARAM_INT);
        return $stmt->execute();
    }




}

?>
