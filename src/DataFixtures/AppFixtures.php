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
            'lesson' => "Le HTML est un langage de balisage utilisé pour représenter les pages web. Il permet de structurer le contenu d'une page web et de lui donner du sens. Il est composé de balises qui délimitent les différents éléments d'une page web. Il est utilisé pour créer des pages web statiques.",
            'boss_name' => 'Tree Sentinel',
            'boss_avatar_url' => 'boss_php.png',
        ],
        [
            'name' => 'Arbre géant maudit',
            'lesson' => "Le CSS est un langage de feuilles de style utilisé pour décrire la présentation d'une page web écrite en HTML ou XML. Il permet de mettre en forme le contenu d'une page web. Il est composé de règles qui définissent comment les éléments HTML doivent être affichés. Il est utilisé pour créer des pages web statiques.",
            'boss_name' => 'Margit',
            'boss_avatar_url' => 'boss_js.png',
        ],
        [
            'name' => 'Forbidden Woods',
            'lesson' => "Le PHP est un langage de programmation libre, principalement utilisé pour produire des pages Web dynamiques via un serveur HTTP, mais pouvant également fonctionner comme n'importe quel langage interprété de façon locale. Il a été initialement créé par Rasmus Lerdorf en 1994; le nom PHP/FI signifie Personal Home Page Tools (« Outils pour la page d'accueil personnelle »). Le langage est aujourd'hui principalement écrit en C.",
            'boss_name' => 'Sorcière de Hemwick',
            'boss_avatar_url' => 'Skelaanvi.png',
        ],
        [
            'name' => 'Nightmare Frontier',
            'lesson' => "Le COBOL est un langage de programmation. Il a été créé en 1959 par Grace Hopper, qui a inventé le premier compilateur. Il permet de programmer des applications de gestion de données. Il est utilisé dans les banques, les assurances, les administrations, les télécommunications, les transports, etc.",
            'boss_name' => 'Amygdala',
            'boss_avatar_url' => 'Zelark.png',
        ],
        [
            'name' => 'Cainhurst Castle',
            'lesson' => "Le JS est un langage de programmation de scripts principalement employé dans les pages web interactives mais aussi pour les serveurs. Il est principalement utilisé pour ajouter des effets dynamiques sur les pages web, mais il est aussi utilisé pour créer des applications desktop et mobiles.",
            'boss_name' => 'Martyr Logarius',
            'boss_avatar_url' => 'Zarorn.png',
        ],
        [
            'name' => 'Hidden Village',
            'lesson' => "Symfony est un framework PHP libre écrit en PHP. Il est distribué sous licence MIT. Il est développé par la société SensioLabs. Il est utilisé pour développer des applications web et des sites web. Il est composé de composants et de bundles qui permettent de créer des applications web.",
            'boss_name' => 'Orphan of Kos',
            'boss_avatar_url' => 'Saudroel.png',
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

            for( $i = 0; $i < 3; $i++ ) 
            {
                $question = new Question();
                $question->setTitle($faker->sentence(20, true));
                $question->setDungeonId($dungeonEntity->getId());
                $question->setRightAnswer("Bonne réponse");
                $question->setWrongAnswer("Mauvaise réponse");
                $manager->persist($question);
            }       

            $manager->flush();
        }

        // Création d'un utilisateur
        $admin = new User();
        $admin->setName('pofpof');
        $admin->setRoles(['ROLE_USER']);
        $admin->setPassword( $this->passwordHasher->hashPassword( $admin, 'pofpof' ));
        $manager->persist($admin);
        $manager->flush();
    }
}
