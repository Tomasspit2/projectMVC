<?php

namespace App\Model;

class AuctionManager extends AbstractManager
{
    public function selectAnnonceAndAuctionById(int $id): mixed
    {
        // Prepare the SQL query to retrieve data from both tables
        $query = "
            SELECT annonces.*, encheres.*
            FROM annonces
            LEFT JOIN encheres ON annonces.id_enchere = encheres.id
            WHERE annonces.id = :id
    ";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function getEnchereId($id)
    {
        $query = "
            SELECT annonces.id_enchere
                FROM annonces
                LEFT JOIN encheres ON annonces.id_enchere = encheres.id
                WHERE annonces.id = :id
    ";
        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return $result['id_enchere'];
    }

    public function selectUserAndEnchere(int $id): mixed
    {
        // Get the auction ID for the given ad ID
        $enchereId = $this->getEnchereId($id);

        // Prepare the SQL query to retrieve data from both tables
        $query = "
            SELECT utilisateurs.*, encheres.*
            FROM utilisateurs
            JOIN encheres ON utilisateurs.id_encheres = encheres.id
            WHERE encheres.id = :enchereId
    ";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':enchereId', $enchereId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function addEnchere($id)
    {
        $enchereId = $this->getEnchereId($id);



        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':enchereId', $enchereId, \PDO::PARAM_INT);
        $statement->execute();
    }
}
