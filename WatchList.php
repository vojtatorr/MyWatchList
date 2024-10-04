<?php

class WatchList
{
    private $dbConn;

    // Constructor, initializes DB connection
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
    }

    // Retrieve all series from the 'series' table
    public function getWatchList()
    {
        $stmt = $this->dbConn->prepare("SELECT * FROM series");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a series to the 'series' table
    public function addSeries($series_name, $img_dir){
    $sql = "INSERT INTO series (series_name, img_dir) VALUES (:series_name, :img_dir)";
    $stmt = $this->dbConn->prepare($sql);
    $stmt->bindParam(':series_name', $series_name, PDO::PARAM_STR);
    $stmt->bindParam(':img_dir', $img_dir, PDO::PARAM_STR); // Insert file name (img_dir)
    return $stmt->execute();
    }

    public function filterSeries($series_name)
    {
        // Základní SQL dotaz
        $sql = "SELECT * FROM series WHERE 1=1";
        $params = [];

        // Přidání podmínek pro filtraci podle parametrů
        if (!empty($series_name)) {
            $sql .= " AND series_name LIKE :series_name";
            $params[':series_name'] = '%' . $series_name . '%';
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
