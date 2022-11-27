<?php

namespace App\Entity;

use App\Repository\DungeonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DungeonRepository::class)]
class Dungeon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $lesson = null;

    #[ORM\Column(length: 60)]
    private ?string $boss_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $boss_avatar_url = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'dungeons', fetch:"EAGER")]
    #[ORM\JoinTable(name: 'user_dungeon')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    public function getLesson(): ?string
    {
        return $this->lesson;
    }

    public function setLesson(string $lesson): self
    {
        $this->lesson = $lesson;

        return $this;
    }

    public function getBossName(): ?string
    {
        return $this->boss_name;
    }

    public function setBossName(string $boss_name): self
    {
        $this->boss_name = $boss_name;

        return $this;
    }

    public function getBossAvatarUrl(): ?string
    {
        return $this->boss_avatar_url;
    }

    public function setBossAvatarUrl(?string $boss_avatar_url): self
    {
        $this->boss_avatar_url = $boss_avatar_url;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addDungeon($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeDungeon($this);
        }

        return $this;
    }
}
