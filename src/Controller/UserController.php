<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Controller\BaseController;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use Twig\Environment;

class UserController extends BaseController
{
    /* ==========================================================================================================================================*/
    /*                                                           Signup                                                                          */
    /* ==========================================================================================================================================*/

    #[Route('/signup', name:'user_signup')]
    public function signup( UserRepository $userRepository, ManagerRegistry $doctrine )
    {
        // Si le formulaire à été soumis
        if(!empty($_POST))
        {
            // Recupére le login saisi par le user dans le champ login + sécurisation
            $name = trim( $_POST['name'] );
            $name = stripslashes($name);
            $name = htmlspecialchars($name);

            // Si le nom ne correspond pas au pattern
            if( !preg_match("/^[A-Za-z-_]+$/",$name) )
            {
                return $this->render('User/signup.html.twig', ['error' => "Name can only contain letter, dashes and underscores"]);
            }

            // Cherche en BDD un user dont le nom est celui saisi
            $existingUser = $userRepository->findOneBy( [ 'name' => $name ] );

            // Vérifie si l'utilisateur existe déjà
            if( $existingUser )
            {
                return $this->render('User/signup.html.twig', ['error' => "Username already taken"]);
            }

            // si pas de correspondance entre les mdp retourne une erreur
            elseif( $_POST['password_1'] != $_POST['password_2'] )
            {
                return $this->render('User/signup.html.twig', ['error' => "Password does not match confirmation"]);
            }

            // Sinon sauvegarder les info en bdd
            else
            {
                // Création du USer
                $newUser = new User();
                $newUser->setName( $name );
                $newUser->setPassword( password_hash( $_POST['password_1'], PASSWORD_DEFAULT ) );

                // Save en BDD
                $entityManager = $doctrine->getManager();
                $entityManager->persist($newUser);
                $entityManager->flush();

                // Connexion du nouvel user
                $_SESSION['connected'] = true;
                $_SESSION['user']      = $newUser;

                // Redirection
                return $this->redirectToRoute('main_map');
            }
        }
        else
        {
            return $this->render('User/signup.html.twig');
        }
    }

    /* ==========================================================================================================================================*/
    /*                                                           Login                                                                           */
    /* ==========================================================================================================================================*/

    #[Route('/login', name:'user_login')]
    public function login( UserRepository $userRepository ):? Response
    {
        if( !empty($_POST) )
        {
            // Recupére le login saisi par le user dans le champ login + sécurisation
            $name = trim( $_POST['name'] );
            $name = stripslashes($name);
            $name = htmlspecialchars($name);

            // Si le nom ne correspond pas au pattern
            if( !preg_match("/^[A-Za-z-_]+$/",$name) )
            {
                return $this->render('User/login.html.twig', ['error' => "Name can only contain letter, dashes and underscores"]);
            }

            // CHercher en BDD un user dont le nom est celui saisi
            $user = $userRepository->findOneBy( [ 'name' => $name ] );

            // Vérifier si le MDP correspond a celui entré dans le formulaire
            if( $user != false && password_verify( $_POST['password'], $user->getPassword() ) ) // Si oui :
            {
                // stocker l'objet User en $_SESSION['user']
                $_SESSION['connected'] = true;
                $_SESSION['user']      = $user;

                // rediriger vers la route /map => header("Location: map")
                return $this->redirectToRoute('main_map');
            }
            else // Sinon :
            {
                // Au cas où, on vide la session
                session_destroy();

                // Afficher le formulaire de login avec un message d'erreur identifiants incorrects
                return $this->render('User/login.html.twig', ['error' => "Invalid credentials"]);
            }
        }
        else
        {
            return $this->render('User/login.html.twig');
        }
    }

    /* ==========================================================================================================================================*/
    /*                                                           Log out                                                                         */
    /* ==========================================================================================================================================*/

    #[Route('/logout', name:'user_logout')]
    public function logout( Environment $twig )
    {
        session_destroy();

        $twig->addGlobal( "connected", false );
        return $this->redirectToRoute('main_home');
    }

    /* ==========================================================================================================================================*/
    /*                                                           Delete                                                                          */
    /* ==========================================================================================================================================*/

    #[Route('/delete', name:'user_delete')]
    public function delete( ManagerRegistry $doctrine, Environment $twig )
    {
        if( ( $_SESSION['connected'] ?? false ) && isset( $_SESSION['user'] ) )
        {
            $entityManager = $doctrine->getManager();
            $entityManager->remove( $entityManager->getRepository(User::class)->find( $_SESSION['user']->getId()) );
            $entityManager->flush();
        }

        session_destroy();

        $twig->addGlobal( "connected", false );
        return $this->render('User/delete.html.twig');
    }
}
