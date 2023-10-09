<?php

namespace App\Model;

use PDO;

class AnnonceManager extends AbstractManager
{
    public const TABLE = 'annonces';

    public function insert(array $annonce): int
    {
        $statement = $this->pdo->prepare("INSERT INTO" . self::TABLE . " (`titre_annonce`) VALUES (:titre_annonce)");
        $statement->bindValue('titre_annonce', $annonce['titre_annonce'], PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
