<?php
class DataContext {
    private $dataContext;

    public function __construct(string $connectionString) {
        try {
            $this->dataContext = new PDO($connectionString);
        } catch(PDOException $e) {
            exit("Error when connecting to database: ". $e->getMessage());
        }
    }

    public function getAll(string $query) {
        $statement = $this->dataContext->query($query);
        $rows = $statement->fetchAll();
        return $rows;
    }

    public function addData(string $query) {
        $statement = $this->dataContext->prepare($query);
        $statement->execute();
    }

    public function updateData(string $query) {
        $statement = $this->dataContext->prepare($query);
        $statement->execute();
    }

    public function execute(string $query) {
        $statement = $this->dataContext->prepare($query);
        $statement->execute();
    }
}
?>