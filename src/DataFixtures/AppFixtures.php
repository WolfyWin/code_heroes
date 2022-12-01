<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

use App\Entity\Dungeon;
use App\Entity\Question;
use App\Entity\User;

//Tout d'abord nous ajoutons la classe Factory de FakerPhp
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) 
    {
        $this->passwordHasher = $passwordHasher;
    }

    public const DUNGEONS = [
        [
            'name' => 'Altus Plateau',
            'lesson' => "Le HTML est un langage de balisage utilisé pour représenter les pages web. Il permet de structurer le contenu d'une page web et de lui donner du sens. Il est composé de balises qui délimitent les différents éléments d'une page web. Il est utilisé pour créer des pages web statiques. Il a été créé par Tim Berners-Lee en 1991.",
            'boss_name' => 'Tree Sentinel',
            'boss_avatar_url' => 'boss_php.png',
            'questions' => [
                [
                    'title' => 'Quel est le langage de programmation utilisé pour créer des pages web ?',
                    'right_answer' => 'HTML',
                    'wrong_answer' => 'COBOL',
                ],
                [
                    'title' => 'Quand a été créé le HTML ?',
                    'right_answer' => '1991',
                    'wrong_answer' => '1980',
                ],
                [
                    'title' => 'Qui est le créateur du HTML ?',
                    'right_answer' => 'Tim Berners-Lee',
                    'wrong_answer' => 'Bill Gates',
                ],
            ]
        ],
        [
            'name' => 'Arbre géant maudit',
            'lesson' => "Le CSS est un langage de feuilles de style utilisé pour décrire la présentation d'une page web écrite en HTML ou XML. Il permet de mettre en forme le contenu d'une page web. Il est composé de règles qui définissent comment les éléments HTML doivent être affichés. Il est utilisé pour créer des pages web statiques. Le concept a été proposé par Håkon Wium Lie et Bert Bos en 1994, mais ce n'est qu'en 1996 que le W3C a publié la première spécification du CSS.",
            'boss_name' => 'Margit',
            'boss_avatar_url' => 'boss_js.png',
            'questions' => [
                [
                    'title' => 'Quel est le langage de programmation utilisé pour mettre en forme une page web ?',
                    'right_answer' => 'CSS',
                    'wrong_answer' => 'PHP',
                ],
                [
                    'title' => 'Quand a été officiellement créé la première spécification du CSS ?',
                    'right_answer' => '1996',
                    'wrong_answer' => '1980',
                ],
                [
                    'title' => 'Qui sont les créateurs du CSS ?',
                    'right_answer' => 'Håkon Wium Lie et Bert Bos',
                    'wrong_answer' => 'Elon Musk et Chuck Norris',
                ],
            ]
        ],
        [
            'name' => 'Forbidden Woods',
            'lesson' => "Le PHP est un langage de programmation libre, principalement utilisé pour produire des pages Web dynamiques via un serveur HTTP, mais pouvant également fonctionner comme n'importe quel langage interprété de façon locale. Il a été initialement créé par Rasmus Lerdorf en 1994; le nom PHP/FI signifie Personal Home Page Tools (« Outils pour la page d'accueil personnelle »). Le langage est aujourd'hui principalement écrit en C.",
            'boss_name' => 'Sorcière de Hemwick',
            'boss_avatar_url' => 'Skelaanvi.png',
            'questions' => [
                [
                    'title' => 'Quel est le langage de programmation utilisé pour créer des pages web dynamiques ?',
                    'right_answer' => 'PHP',
                    'wrong_answer' => 'C++',
                ],
                [
                    'title' => 'Quand a été créé le PHP ?',
                    'right_answer' => '1994',
                    'wrong_answer' => '1980',
                ],
                [
                    'title' => 'Qui est le créateur du PHP ?',
                    'right_answer' => 'Rasmus Lerdorf',
                    'wrong_answer' => 'Hideo Kojima',
                ],
            ]
        ],
        [
            'name' => 'Nightmare Frontier',
            'lesson' => "Le COBOL est un langage de programmation. Il a été créé en 1959 par Grace Hopper, qui a inventé le premier compilateur. Il permet de programmer des applications de gestion de données. Il est utilisé dans les banques, les assurances, les administrations, les télécommunications, les transports, etc.",
            'boss_name' => 'Amygdala',
            'boss_avatar_url' => 'Zelark.png',
            'questions' => [
                [
                    'title' => 'Quel est le langage de programmation utilisé pour programmer des applications de gestion de données ?',
                    'right_answer' => 'COBOL',
                    'wrong_answer' => 'LaTeX',
                ],
                [
                    'title' => 'Quand a été créé le COBOL ?',
                    'right_answer' => 'En 1959, pendant la guerre froide',
                    'wrong_answer' => 'En 1618, pendant la guerre des croissants au beurre',
                ],
                [
                    'title' => 'Qui est le créateur du COBOL ?',
                    'right_answer' => 'Grace Hopper, professeur de mathématiques à l\'université de Harvard',
                    'wrong_answer' => 'Guy St-Hilaire, professeur d\'arithmétique au Lycée Bréval de Montréal',
                ],
            ]
        ],
        [
            'name' => 'Cainhurst Castle',
            'lesson' => "Le JS est un langage de programmation de scripts principalement employé dans les pages web interactives mais aussi pour les serveurs. Il est principalement utilisé pour ajouter des effets dynamiques sur les pages web, mais il est aussi utilisé pour créer des applications desktop et mobiles.",
            'boss_name' => 'Martyr Logarius',
            'boss_avatar_url' => 'Zarorn.png',
            'questions' => [
                [
                    'title' => 'Quel est le langage de programmation utilisé pour ajouter des effets dynamiques sur les pages web ?',
                    'right_answer' => 'JS',
                    'wrong_answer' => 'Lua',
                ],
                [
                    'title' => 'Quand a été créé le JS ?',
                    'right_answer' => '1995',
                    'wrong_answer' => '1480',
                ],
                [
                    'title' => 'Qui est le créateur du JS ?',
                    'right_answer' => 'Brendan Eich',
                    'wrong_answer' => 'Jean-Sébastien Bach',
                ],
            ]
        ],
        [
            'name' => 'Hidden Village',
            'lesson' => "Symfony est un framework PHP libre écrit en PHP. Il est distribué sous licence MIT. Il est développé par la société SensioLabs. Il est utilisé pour développer des applications web et des sites web. Il est composé de composants et de bundles qui permettent de créer des applications web.",
            'boss_name' => 'Orphan of Kos',
            'boss_avatar_url' => 'Saudroel.png',
            'questions' => [
                [
                    'title' => 'Quel est le framework PHP utilisé pour développer des applications web ?',
                    'right_answer' => 'Symfony',
                    'wrong_answer' => 'RPG Maker',
                ],
                [
                    'title' => 'Quand a été créé Symfony ?',
                    'right_answer' => '2005',
                    'wrong_answer' => '1980',
                ],
                [
                    'title' => 'Qui est le créateur de Symfony ?',
                    'right_answer' => 'Fabien Potencier',
                    'wrong_answer' => 'Jean-Michel Jarre',
                ],
            ]
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        //Puis ici nous demandons à la Factory de nous fournir un Faker
        $faker = Factory::create();

        // Création des donjons a partir de la constante de classe et 3 questions pour chaque donjon
        foreach( self::DUNGEONS as $dungeon ) 
        {
            $dungeonEntity = new Dungeon();
            $dungeonEntity->setName($dungeon['name']);
            $dungeonEntity->setLesson($dungeon['lesson']);
            $dungeonEntity->setBossName($dungeon['boss_name']);
            $dungeonEntity->setBossAvatarUrl($dungeon['boss_avatar_url']);
            $manager->persist($dungeonEntity);
            $manager->flush();

            // Création des questions pour chaque donjon
            foreach( $dungeon['questions'] as $question ) 
            {
                $questionEntity = new Question();
                $questionEntity->setTitle($question['title']);
                $questionEntity->setRightAnswer($question['right_answer']);
                $questionEntity->setWrongAnswer($question['wrong_answer']);
                $questionEntity->setDungeonID($dungeonEntity->getId());
                $manager->persist($questionEntity);
                $manager->flush();
            }
            $manager->flush();
        }

        // Création d'un utilisateur
        $admin = new User();
        $admin->setName('admin');
        $admin->setRoles(['ROLE_USER']);
        $admin->setPassword( $this->passwordHasher->hashPassword( $admin, 'pofpof' ));
        $manager->persist($admin);
        $manager->flush();
    }
}
