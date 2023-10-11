<?php

namespace App\Model;

class AuctionManager extends AbstractManager
{
    public function selectAnnonceAndAuctionById(int $id): array
    {
        // Prepare the SQL query to retrieve data from both tables
        $query = "
        SELECT annonces.*, encheres.*
        FROM annonces
        LEFT JOIN encheres ON annonces.id_enchere = encheres.id
        WHERE annonces.id=:id
    ";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }
}
