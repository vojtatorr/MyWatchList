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
    public function addSeries($series_name, $img_dir/* , $finished */)
    {
        $sql = "INSERT INTO series (series_name, img_dir/* , finished */) VALUES (:series_name, :img_dir/* , :finished */)";
        $stmt = $this->dbConn->prepare($sql);
        $stmt->bindParam(':series_name', $series_name, PDO::PARAM_STR);
        $stmt->bindParam(':img_dir', $img_dir, PDO::PARAM_STR);
        /* $stmt->bindParam(':finished', $finished, PDO::PARAM_BOOL); */
        return $stmt->execute();
    }
}

?>