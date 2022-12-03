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
    }
 ?>