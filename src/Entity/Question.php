<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $right_answer = null;

    #[ORM\Column(length: 255)]
    private ?string $wrong_answer = null;

    #[ORM\Column]
    private ?int $dungeon_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getRightAnswer(): ?string
    {
        return $this->right_answer;
    }

    public function setRightAnswer(string $right_answer): self
    {
        $this->right_answer = $right_answer;

        return $this;
    }

    public function getWrongAnswer(): ?string
    {
        return $this->wrong_answer;
    }

    public function setWrongAnswer(string $wrong_answer): self
    {
        $this->wrong_answer = $wrong_answer;

        return $this;
    }

    public function getDungeonId(): ?int
    {
        return $this->dungeon_id;
    }

    public function setDungeonId(int $dungeon_id): self
    {
        $this->dungeon_id = $dungeon_id;

        return $this;
    }
}
