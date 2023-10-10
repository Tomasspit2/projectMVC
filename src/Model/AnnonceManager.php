<?php

namespace App\Model;

use PDO;

class AnnonceManager extends AbstractManager
{
    public const TABLE = 'annonces';

    public function insert(array $annonce, array $file): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE .
            " (`titre_annonce`, `prix_depart`,`date_fin_enchere`, `modele`,
            `marque`, `puissance`, `annee`, `description`, `photo`)
            VALUES (:titre_annonce, :prix_depart, :date_fin_enchere, :modele,
            :marque, :puissance, :annee, :description, :photo)");
        $statement->bindValue('titre_annonce', $annonce['titre_annonce'], PDO::PARAM_STR);
        $statement->bindValue('prix_depart', $annonce['prix_depart'], PDO::PARAM_INT);
        $statement->bindValue('date_fin_enchere', $annonce['date_fin_enchere'], PDO::PARAM_INT);
        $statement->bindValue('modele', $annonce['modele'], PDO::PARAM_STR);
        $statement->bindValue('marque', $annonce['marque'], PDO::PARAM_STR);
        $statement->bindValue('puissance', $annonce['puissance'], PDO::PARAM_INT);
        $statement->bindValue('annee', $annonce['annee'], PDO::PARAM_INT);
        $statement->bindValue('description', $annonce['description'], PDO::PARAM_STR);
        $statement->bindValue('photo', $file['photo']['name'], PDO::PARAM_STR);

        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
    public function update(array $annonce): bool
    {
        $statement = $this->pdo->prepare("UPDATE" . self::TABLE . " SET `titre-annonce` = :titre-annonce WHERE id=:id");
        $statement->bindValue('id', $annonce['id'], PDO::PARAM_INT);
        $statement->bindValue('titre-annonce', $annonce['titre_annonce'], PDO::PARAM_STR);
        return $statement->execute();
    }
}
