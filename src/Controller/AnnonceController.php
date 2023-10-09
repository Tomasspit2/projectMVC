<?php

namespace App\Controller;

use App\Model\AnnonceManager;

class AnnonceController extends AbstractController
{
    /**
     * List Annonces
     * @return string
     */
    public function index(): string
    {
        $annonceManager = new AnnonceManager();
        $annonces = $annonceManager->selectAll('titre_annonce');

        return $this->twig->render(
            'Annonce/index.html.twig',
            ['annonces' => $annonces]
        );
    }

    /**
     * Show informations for a specific annonce
     * @param int $id
     * @return string
     */
    public function show(int $id): string
    {
        $annonceManager = new AnnonceManager();
        $annonce = $annonceManager->selectOneById($id);

        return $this->twig->render(
            'annonce/show.html.twig',
            ['annonce' => $annonce]
        );
    }

    /**
     * Edit a specific annonce
     * @param int $id
     * @return string|null
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

            // we are redirecting so we don't want any content rendered
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
     * Add a new annonce
     * @return string|null
     */
    public function add(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // clean $_POST data
            $annonce = array_map('trim', $_POST);

            // TODO validations (length, format...)

            // if validation is ok, insert and redirection
            $annonceManager = new AnnonceManager();
            $id = $annonceManager->insert($annonce);

            header('Location:/annonce/show?id=' . $id);
            return null;
        }

        return $this->twig->render('Annonce/add.html.twig');
    }

    /**
     * Delete a specific annonce
     * @param int $id
     */
    public function delete(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = trim($_POST['id']);
            $itemManager = new AnnonceManager();
            $itemManager->delete((int)$id);

            header('Location:/annonce');
        }
    }
}
