<?php

class WatchList
{
    private $dbConn;

    // Constructor, initializes DB connection
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    // Retrieve all shows from the 'shows' table
    public function getWatchList()
    {
        $stmt = $this->dbConn->prepare("SELECT * FROM shows");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a shows to the 'shows' table
    public function addShows($shows_name, $img_dir, $show_status){
    $sql = "INSERT INTO shows (shows_name, img_dir, show_status) VALUES (:shows_name, :img_dir, :show_status)";
    $stmt = $this->dbConn->prepare($sql);
    $stmt->bindParam(':shows_name', $shows_name, PDO::PARAM_STR);
    $stmt->bindParam(':img_dir', $img_dir, PDO::PARAM_STR); // Insert file name (img_dir)
    $stmt->bindParam(':show_status', $show_status, PDO::PARAM_INT);

    return $stmt->execute();
    }

    public function filtershows($shows_name)
    {
        // Základní SQL dotaz
        $sql = "SELECT * FROM shows WHERE 1=1";
        $params = [];

        // Přidání podmínek pro filtraci podle parametrů
        if (!empty($shows_name)) {
            $sql .= " AND shows_name LIKE :shows_name";
            $params[':shows_name'] = '%' . $shows_name . '%';
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
}

?>
