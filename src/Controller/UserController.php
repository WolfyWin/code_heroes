<?php

namespace App\Controller;

use Twig\Environment;

use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\PhpBridgeSessionStorage;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Http\SecurityEvents;

use App\Security\UserAuthenticatorInterface;
use App\Security\SecurityAuthenticator;



use App\Entity\User;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    /* ==========================================================================================================================================*/
    /*                                                           Signup                                                                          */
    /* ==========================================================================================================================================*/

    // #[Route('/signup', name:'user_signup')]
    // public function signup( UserRepository $userRepository, ManagerRegistry $doctrine )
    // {
    //     // Si le formulaire à été soumis
    //     if(!empty($_POST))
    //     {
    //         // Recupére le login saisi par le user dans le champ login + sécurisation
    //         $name = trim( $_POST['name'] );
    //         $name = stripslashes($name);
    //         $name = htmlspecialchars($name);

    //         // Si le nom ne correspond pas au pattern
    //         if( !preg_match("/^[A-Za-z-_]+$/",$name) )
    //         {
    //             return $this->render('User/signup.html.twig', ['error' => "Name can only contain letter, dashes and underscores"]);
    //         }

    //         // Cherche en BDD un user dont le nom est celui saisi
    //         $existingUser = $userRepository->findOneBy( [ 'name' => $name ] );

    //         // Vérifie si l'utilisateur existe déjà
    //         if( $existingUser )
    //         {
    //             return $this->render('User/signup.html.twig', ['error' => "Username already taken"]);
    //         }

    //         // si pas de correspondance entre les mdp retourne une erreur
    //         elseif( $_POST['password_1'] != $_POST['password_2'] )
    //         {
    //             return $this->render('User/signup.html.twig', ['error' => "Password does not match confirmation"]);
    //         }

    //         // Sinon sauvegarder les info en bdd
    //         else
    //         {
    //             // Création du User
    //             $newUser = new User();
    //             $newUser->setName( $name );
    //             $newUser->setPassword( password_hash( $_POST['password_1'], PASSWORD_DEFAULT ) );

    //             // Save en BDD
    //             $entityManager = $doctrine->getManager();
    //             $entityManager->persist($newUser);
    //             $entityManager->flush();

    //             // Redirection
    //             return $this->redirectToRoute('main_map');
    //         }
    //     }
    //     else
    //     {
    //         return $this->render('User/signup.html.twig');
    //     }
    // }

    #[Route('/signup', name: 'user_signup')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ) 
        {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $this->addFlash('success', 'Compte créé avec succès, vous pouvez vous connecter :)');
            return $this->redirectToRoute('user_login');
        }

        return $this->render('User/signup.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /* ==========================================================================================================================================*/
    /*                                                           Login                                                                           */
    /* ==========================================================================================================================================*/

    #[Route('/login', name: 'user_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('User/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /* ==========================================================================================================================================*/
    /*                                                           Delete                                                                          */
    /* ==========================================================================================================================================*/

    #[Route('/delete', name:'user_delete')]
    public function delete( ManagerRegistry $doctrine, Environment $twig )
    {
        if( $this->getUser() )
        {
            $entityManager = $doctrine->getManager();
            $entityManager->remove( $this->getUser() );
            $entityManager->flush();
            
            $this->container->get('security.token_storage')->setToken(null);
        }
        
        return $this->render('User/delete.html.twig');
    }
}
