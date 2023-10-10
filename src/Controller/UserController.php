<?php

namespace App\Controller;

use App\Model\UserManager;

class UserController extends AbstractController
{
    public function add(): string
    {
        $userAdd['lastname'] = $userAdd['firstname'] = $userAdd['email'] = $userAdd['password'] = "";
        $errors['lastname'] = $errors['firstname'] = $errors['email'] = $errors['password'] = "";
        function checkdata($data): string
        {
            $data = trim($data);
            $data = htmlspecialchars($data);
            $data = htmlentities($data);
            return $data;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['lastname']) | empty(trim($_POST['lastname']))) {
                $errors['lastname'] = "Le nom est obligatoire";
            } else {
                $userAdd['lastname'] = checkdata($_POST['lastname']);
            }
            if (!isset($_POST['firstname']) | empty(trim($_POST['firstname']))) {
                $errors['firstname'] = "Le prenom est obligatoire";
            } else {
                $userAdd['firstname'] = checkdata($_POST['firstname']);
            }
            if (!isset($_POST['email']) | empty(trim($_POST['email']))) {
                $errors['email'] = "L'email est obligatoire";
            } else {
                $userEmailCheck = checkdata($_POST['email']);
                $userManager = new UserManager();
                $userToCheck = $userManager->selectOneByEmail($userEmailCheck);
                if (empty($userToCheck)) {
                    $userAdd['email'] = checkdata($_POST['email']);
                } else {
                    $errors['email'] = "Cet email est déjà utilisé";
                    $userAdd['email'] = checkdata($_POST['email']);
                }
            }
            if (
                !isset($_POST['password'])
                | empty(trim($_POST['password']))
            ) {
                $errors['password'] = "Le mot de passe est obligatoire";
            } else {
                $userAdd['password'] = checkdata($_POST['password']);
                if (
                    empty($errors['lastname']) && empty($errors['email'])
                    && empty($errors['password']) && empty($errors['firstname'])
                ) {
                    $userManager = new UserManager();
                    $lastUser = $userManager->addUser($_POST);
                    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != "") {
                        header('Location:/');
                    } else {
                        $userManager = new UserManager();
                        $lastUser = $userManager->selectOneById($lastUser);

                        $user = $userManager->selectOneByEmail($lastUser['email']);
                        $_SESSION['user'] = $user;
                        $_SESSION['user_id'] = $_SESSION['user']['id'];
                        header('Location:/');
                    }
                }
            }
        }
        return $this->twig->render('register.html.twig', ['errors' => $errors, 'userAdd' => $userAdd]);
    }
}
