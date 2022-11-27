<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Dungeon::class, inversedBy: 'users', fetch:"EAGER")]
    #[ORM\JoinTable(name: 'user_dungeon')]
    public Collection $dungeons;

    public function __construct()
    {
        $this->dungeons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    // Méthode qui retourne les id des donjons de la propriété dungeons dans un tableau indexé
    public function getDungeonsId(): array
    {
        $dungeonsId = [];
        foreach( $this->dungeons as $dungeon )
        {
            $dungeonsId[] = $dungeon->getId();
        }
        return $dungeonsId;
    }


    /**
     * @return Collection<int, Dungeon>
     */
    public function getDungeons(): Collection
    {
        return $this->dungeons;
    }

    public function addDungeon(Dungeon $dungeon): self
    {
        if (!$this->dungeons->contains($dungeon)) {
            $this->dungeons->add($dungeon);
        }

        return $this;
    }

    public function removeDungeon(Dungeon $dungeon): self
    {
        $this->dungeons->removeElement($dungeon);

        return $this;
    }
}
