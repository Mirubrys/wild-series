<?php

namespace App\Entity;

use App\Repository\EpisodeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EpisodeRepository::class)
 */
class Episode
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Le champs est vide, veuillez saisir un titre."
     * )
     * @Assert\Length(
     *     max="255",
     *     maxMessage="Le titre fournit {{ value }} est beaucoup trop long (255 caractères max)."
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *     message="Le champs est vide, veuillez saisir un numéro d'épisode."
     * )
     * @Assert\Positive(
     *     message="Le numéro d'épisode doit être suprieur à zéro."
     * )
     */
    private $number;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *     message="Le champs est vide, veuillez saisir un synopsis pour l'épisode."
     * )
     */
    private $synopsis;

    /**
     * @ORM\ManyToOne(targetEntity=Season::class, inversedBy="episodes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $season;

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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): self
    {
        $this->season = $season;

        return $this;
    }
}
