<?php

namespace App\Controller;

use App\Model\AnnonceManager;
use App\Model\AuctionManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AnnonceController extends AbstractController
{
    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function index($marque = " "): string
    {
        $annonceManager = new AnnonceManager();

        if ($marque != " ") {
            $annonces = $annonceManager->filterMarque($marque);
        } else {
            $annonces = $annonceManager->selectAll('marque');
        }
        $userData = $_SESSION['user_id'] ?? [];
        return $this->twig->render(
            'Annonce/index.html.twig',
            ['annonces' => $annonces,
                'userData' => $userData]
        );
    }

    /**
     * @throws RuntimeError
     * @throws LoaderError
     * @throws SyntaxError
     */
    public function show(int $id): string
    {
        $annonceManager = new AnnonceManager();
        $annonce = $annonceManager->selectOneById($id);
        $enchereManager = new AuctionManager();
        $enchere = $enchereManager->selectNameAnnonceMontant($id);


        $userData = $_SESSION['user_id'] ?? [];
        $enchereForm = $_POST;

        $errors = [
            'montant' => '',
            ];
        function checkdata($data): string
        {
            $data = trim($data);
            $data = htmlspecialchars($data);
            return htmlentities($data);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['montant']) | empty(trim($_POST['montant']))) {
                $errors['montant'] = 'Ce champs est obligatoire.';
            } else {
                $enchereForm['montant'] = checkdata($_POST['montant']);
            }

            $montantEnBase = new AuctionManager();
                $montantEnBase = $montantEnBase->selectMontantAnnonce($id);

            if ($enchereForm['montant'] < $montantEnBase) {
                $errors['montant'] = 'Le montant doit être supérieur à celui enregistré en base.';
            } else {
                $auctionManager = new AuctionManager();
                $auctionManager->addEnchere($_POST, $enchereForm);

                header('Location: ' . $_SERVER['REQUEST_URI']);
            }


        }

        return $this->twig->render(
            'annonce/show.html.twig',
            [
                'annonce' => $annonce,
               'enchere' => $enchere,
                'userData' => $userData,
            ]
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function edit(int $id): ?string
    {
        $annonceManager = new AnnonceManager();
        $annonce = $annonceManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $annonce = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, update and redirection
            $annonceManager->update($annonce);

            header('Location: /annonce/show?id=' . $id);

            // we are redirecting, so we don't want any content rendered
            return null;
        }

        return $this->twig->render(
            'Annonce/edit.html.twig',
            [
                'annonce' => $annonce,
            ]
        );
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function add(): ?string
    {
        $userData = $_SESSION['user_id'];
        $annonce = $errors = [
            'description' => '',
            'photo' => '',
            'annee' => '',
            'puissance' => '',
            'marque' => '',
            'modele' => '',
            'date_fin_enchere' => '',
            'prix_depart' => '',
            'titre_annonce' => '',
        ];
        function checkdata($data): string
        {
            $data = trim($data);
            $data = htmlspecialchars($data);
            return htmlentities($data);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $uploadDir = "../public/assets/images/";
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $uploadFile = $uploadDir . $_FILES['photo']['name'];
            move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile);
            $authorizedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $maxFileSize = 1000000;

            if ((!in_array($extension, $authorizedExtensions))) {
                $errors['photo'] = 'Veuillez sélectionner une image de type Jpg ou Jpeg ou PNG ou gif ou webp.';
            }
            if (file_exists($_FILES['photo']['name']) && filesize($_FILES['photo']['tmp_name']) > $maxFileSize) {
                $errors['photo'] = 'Voter fichier doit faire moins de 1Mo.';
            }
            if (!isset($_POST['description']) | empty(trim($_POST['description']))) {
                $errors['description'] = 'Ce champs est obligatoire.';
            } else {
                $annonce['description'] = checkdata($_POST['description']);
            }
            if (!isset($_POST['annee']) | empty(trim($_POST['annee']))) {
                $errors['annee'] = 'Ce champs est obligatoire.';
            } else {
                $annonce['annee'] = checkdata($_POST['annee']);
            }
            if (!isset($_POST['puissance']) | empty(trim($_POST['puissance']))) {
                $errors['puissance'] = 'Ce champs est obligatoire.';
            } else {
                $annonce['puissance'] = checkdata($_POST['puissance']);
            }
            if (!isset($_POST['marque']) | empty(trim($_POST['marque']))) {
                $errors['marque'] = 'Ce champs est obligatoire.';
            } else {
                $annonce['marque'] = checkdata($_POST['marque']);
            }
            if (!isset($_POST['modele']) | empty(trim($_POST['modele']))) {
                $errors['modele'] = 'Ce champs est obligatoire.';
            } else {
                $annonce['modele'] = checkdata($_POST['modele']);
            }
            if (!isset($_POST['date_fin_enchere']) | empty(trim($_POST['date_fin_enchere']))) {
                $errors['date_fin_enchere'] = 'Ce champs est obligatoire.';
            } else {
                $annonce['date_fin_enchere'] = checkdata($_POST['date_fin_enchere']);
            }
            if (!isset($_POST['prix_depart']) | empty(trim($_POST['prix_depart']))) {
                $errors['prix_depart'] = 'Ce champs est obligatoire.';
            } else {
                $annonce['prix_depart'] = checkdata($_POST['prix_depart']);
            }
            if (!isset($_POST['titre_annonce']) | empty(trim($_POST['titre_annonce']))) {
                $errors['titre_annonce'] = 'Ce champs est obligatoire.';
            } else {
                $annonce['titre_annonce'] = checkdata($_POST['titre_annonce']);
            }
            if (
                $errors['photo'] != ""
                | $errors['description'] != ""
                | $errors['annee'] != ""
                | $errors['puissance']  != ""
                | $errors['marque'] != ""
                | $errors['modele'] != ""
                | $errors['date_fin_enchere'] != ""
                | $errors['prix_depart'] != ""
                | $errors['titre_annonce'] != ""
            ) {
                return $this->twig->render(
                    'annonce/add.html.twig',
                    ['error' => $errors, 'annonce' => $annonce, 'userData' => $userData]
                );
            } else {
                $productManager = new AnnonceManager();
                $productManager->insert($_POST, $_FILES);
                header('Location:/annonce');
            }
        }

        return $this->twig->render(
            'Annonce/add.html.twig',
            ['error' => $errors ,'annonce' => $annonce, 'userData' => $userData]
        );
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $itemManager = new AnnonceManager();
            $itemManager->delete((int)$id);

            header('Location:/annonce');
        }
    }
}
