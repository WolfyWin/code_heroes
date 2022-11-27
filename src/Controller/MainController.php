<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\DungeonRepository;
use App\Repository\QuestionRepository;
use App\Controller\BaseController;

use App\Entity\Dungeon;
use App\Entity\Question;
use App\Entity\User;

class MainController extends BaseController
{
    #[Route('/', name:'main_home')]
    public function home(): Response
    {
        // Si on est déjà connecté on envoi directement vers la map
        if( isset( $_SESSION['connected'] ) )
        {
            header('Location: ./map');
            exit();
        }
        return $this->render('Main/home.html.twig');
    }

    #[Route('/map', name:'main_map')]
    public function map(DungeonRepository $dungeonRepository): Response
    {
        // Si on est PAS connecté on renvoi directement vers l'accueil
        if( !isset( $_SESSION['connected'] ) )
        {
            header('Location: ./');
            exit();
        }

        $allDungeons = $dungeonRepository->findAll();

        return $this->render('Main/map.html.twig', [
            "allDungeons"       => $allDungeons
        ]);
    }

    #[Route('/dungeon/{id}', name:'main_dungeon')]
    public function dungeon(Dungeon $theDungeon): Response
    {
        // Si on est PAS connecté on renvoi directement vers l'accueil
        if( !isset( $_SESSION['connected'] ) )
        {
            header('Location: ./');
            exit();
        }


        if($theDungeon != false)
        {
            return $this->render('Main/dungeon.html.twig', [ "theDungeon" => $theDungeon ]);
        }

        // redirection vers la map si le donjon n'existe pas
        else
        {
            header('Location: ./map');
            exit();
        }
    }

    #[Route('/dungeon/{id}/lesson', name:'main_lesson')]
    public function lesson(Dungeon $theDungeon): Response
    {
        // Si on est PAS connecté on renvoi directement vers l'accueil
        if( !isset( $_SESSION['connected'] ) )
        {
            header('Location: ./');
            exit();
        }

        if($theDungeon != false)
        {
            return $this->render('Main/lesson.html.twig', [ "theDungeon" => $theDungeon ]);;
        }

        // redirection vers la map si le donjon n'existe pas
        else
        {
            header('Location: ./map');
            exit();
        }
    }

    #[Route('/dungeon/{id}/battle/{round}', methods: ['GET'], name:'main_battle')]
    public function battle( QuestionRepository $questionRepository , Dungeon $theDungeon, int $round = 0 ): Response
    {
        // Si on est PAS connecté on renvoi directement vers l'accueil
        if( !isset( $_SESSION['connected'] ) )
        {
            header('Location: ./');
            exit();
        }

        // RÉcupération de la question a partir de l'id du donjon et du numéro de round avec le questionRepository
        $theQuestion = $questionRepository->findBy( ['dungeon_id' => $theDungeon->getId() ], ['id' => 'ASC'] )[$round];

        if( $theDungeon != false AND $theQuestion != false )
        {
            // On met les questions dans un tableau qu'on mélange avec shuffle()
            $answers = [
                $theQuestion->getRightAnswer(),
                $theQuestion->getWrongAnswer(),
            ];
            shuffle( $answers );

            return $this->render('Main/battle.html.twig', [
                "theDungeon"  => $theDungeon,
                "theQuestion" => $theQuestion,
                "answers"     => $answers,
                "round"       => $round
            ]);
        }
        else
        {
            // redirection vers la map si le donjon ou la question n'existe pas
            header('Location: ./map');
            exit();
        }
    }

    #[Route('/dungeon/{id}/battle/{round}', methods: ['POST'], name:'main_answer')]
    public function answer(ManagerRegistry $doctrine, Dungeon $theDungeon, int $round = 0,): Response
    {
        $entityManager = $doctrine->getManager();

        // Si on est PAS connecté on renvoi directement vers l'accueil
        if( !isset( $_SESSION['connected'] ) )
        {
            header('Location: ./');
            exit();
        }

        $questionModel = new Question();
        $theQuestion = $entityManager->getRepository(Question::class)->findBy( ['dungeon_id' => $theDungeon->getId() ], ['id' => 'ASC'] )[$round];

        // Récupération de la question a partir de son id et de la réponse
        // Retourne false si la réponse n'est pas bonne, true sinon
        $isRight = $entityManager->getRepository(Question::class)->findOneBy( ['dungeon_id' => $theDungeon->getId(), 'right_answer' => $_POST['answer'] ] ) != false;

        // Vérification de l'input utilisateur
        if( $theDungeon != false AND $theQuestion != false AND $round <= 2 )
        {
            // Pour l'instant, 3 questions par donjon
            if( $round == 2 && $isRight )
            {
                $user = $entityManager->getRepository(User::class)->findOneBy( ['id' => $_SESSION['user']->getId() ] );
                $user->addDungeon( $theDungeon );
                $entityManager->persist( $user );                
                $entityManager->flush();

                $theDungeon->addUser( $user );
                $entityManager->persist( $theDungeon );                
                $entityManager->flush();

                $_SESSION['user'] = $user;
            }

            return $this->render('Main/answer.html.twig', [
                "theDungeon" => $theDungeon,
                "right"      => $isRight,
                "round"      => $round
            ]);
        }
        else
        {
            // redirection vers la map si le donjon ou la question n'existe pas
            header('Location: ./map');
            exit();
        }
    }
}
