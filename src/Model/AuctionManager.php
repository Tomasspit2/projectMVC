<?php

namespace App\Model;

use DateTimeImmutable;

class AuctionManager extends AbstractManager
{
    public function addEnchere($id, array $enchereForm)
    {
        $user_id = $_SESSION['user_id'];
        $annonceId = (int)$_GET['id'];


        // 1. Insérer une nouvelle enchère
        $insertQuery = "INSERT INTO encheres (`date`, `montant`) VALUES (current_date(), :montant)";
        $insertStatement = $this->pdo->prepare($insertQuery);
        $insertStatement->bindValue('montant', $enchereForm['montant'], \PDO::PARAM_INT);
        $insertStatement->execute();

        // 2. Récupérer l'ID de la nouvelle enchère insérée
        $enchereId = $this->pdo->lastInsertId();

        // 3. Insérer une ligne dans la table utilisateurs_encheres
        $insertTableMany = "INSERT INTO utilisateurs_encheres (`id_utilisateur`, `id_enchere`) VALUES (:id_utilisateur, :id_enchere)";
        $insertStatementMany = $this->pdo->prepare($insertTableMany);
        $insertStatementMany->bindValue('id_utilisateur', $user_id, \PDO::PARAM_INT);
        $insertStatementMany->bindValue('id_enchere', $enchereId, \PDO::PARAM_INT);
        $insertStatementMany->execute();

        // 4. Mettre à jour la table annonces avec l'ID de l'enchère
        $updateQuery = "UPDATE annonces SET id_enchere = :id_enchere WHERE id = :id";
        $updateStatement = $this->pdo->prepare($updateQuery);
        $updateStatement->bindValue('id_enchere', $enchereId, \PDO::PARAM_INT);
        $updateStatement->bindValue('id', $annonceId, \PDO::PARAM_INT);
        $updateStatement->execute();
    }

    public function selectNameAnnonceMontant(int $id): array | false
    {
        $query = "SELECT u.nom, u.prenom, e.date, a.id AS annonce_id, subquery.montant_max
    FROM utilisateurs AS u
    JOIN utilisateurs_encheres AS ue ON u.id = ue.id_utilisateur
    JOIN encheres AS e ON ue.id_enchere = e.id
    JOIN annonces AS a ON e.id = a.id_enchere
    JOIN (
        SELECT a.id AS annonce_id, MAX(e.montant) AS montant_max
        FROM annonces AS a
        LEFT JOIN encheres AS e ON a.id_enchere = e.id
        WHERE a.id = :id
        GROUP BY a.id
    ) AS subquery ON a.id = subquery.annonce_id;";
        $queryStatement = $this->pdo->prepare($query);
        $queryStatement->bindValue('id', $id, \PDO::PARAM_INT);
        $queryStatement->execute();

        return $queryStatement->fetch(\PDO::FETCH_ASSOC);
    }
    public function selectMontantAnnonce()
    {
        try {
            $annonceId = (int)$_GET['id'];
            $query = "SELECT e.montant FROM encheres AS e
                  JOIN annonces AS a ON e.id = a.id_enchere
                  WHERE a.id = :id";
            $queryStatement = $this->pdo->prepare($query);
            $queryStatement->bindValue('id', $annonceId, \PDO::PARAM_INT);
            $queryStatement->execute();

            $result = $queryStatement->fetch(\PDO::FETCH_ASSOC);

            if ($result === false) {
                // No data found for the given ID
                return null;
            }

            return $result['montant'];
        } catch (\Exception $e) {
            // Handle any exceptions or errors that occur during the query
            // You can log the error or return an error message
            // For debugging purposes, you can echo or log the error message
            echo 'Error: ' . $e->getMessage();
            return null;
        }
    }

    public function getMinAmount()
    {
        $annonceId = (int)$_GET['id'];
        $query = "SELECT prix_depart FROM annonces WHERE id = :id";
        $queryStatement = $this->pdo->prepare($query);
        $queryStatement->bindValue(':id', $annonceId, \PDO::PARAM_INT);
        $queryStatement->execute();
        $result = $queryStatement->fetch(\PDO::FETCH_ASSOC);
        return $result['prix_depart'];
    }
}
