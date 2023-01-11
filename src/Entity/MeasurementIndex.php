<?php

namespace App\Entity;

use App\Repository\MeasurementIndexRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeasurementIndexRepository::class)
 */
class MeasurementIndex
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $whatWeMeasure;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $howWeMeasure;

    /**
     * @ORM\ManyToMany(targetEntity=SonataUserUser::class, inversedBy="measurementIndices")
     */
    private $researchers;

    /**
     * @ORM\OneToMany(targetEntity=SurveyQuestion::class, mappedBy="measurementIndex", orphanRemoval=true)
     */
    private $surveyQuestions;

    public function __construct()
    {
        $this->researchers = new ArrayCollection();
        $this->surveyQuestions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getWhatWeMeasure(): ?string
    {
        return $this->whatWeMeasure;
    }

    public function setWhatWeMeasure(?string $whatWeMeasure): self
    {
        $this->whatWeMeasure = $whatWeMeasure;

        return $this;
    }

    public function getHowWeMeasure(): ?string
    {
        return $this->howWeMeasure;
    }

    public function setHowWeMeasure(?string $howWeMeasure): self
    {
        $this->howWeMeasure = $howWeMeasure;

        return $this;
    }

    /**
     * @return Collection<int, SonataUserUser>
     */
    public function getResearchers(): Collection
    {
        return $this->researchers;
    }

    public function addResearcher(SonataUserUser $researcher): self
    {
        if (!$this->researchers->contains($researcher)) {
            $this->researchers[] = $researcher;
        }

        return $this;
    }

    public function removeResearcher(SonataUserUser $researcher): self
    {
        $this->researchers->removeElement($researcher);

        return $this;
    }

    /**
     * @return Collection<int, SurveyQuestion>
     */
    public function getSurveyQuestions(): Collection
    {
        return $this->surveyQuestions;
    }

    public function addSurveyQuestion(SurveyQuestion $surveyQuestion): self
    {
        if (!$this->surveyQuestions->contains($surveyQuestion)) {
            $this->surveyQuestions[] = $surveyQuestion;
            $surveyQuestion->setMeasurementIndex($this);
        }

        return $this;
    }

    public function removeSurveyQuestion(SurveyQuestion $surveyQuestion): self
    {
        if ($this->surveyQuestions->removeElement($surveyQuestion)) {
            // set the owning side to null (unless already changed)
            if ($surveyQuestion->getMeasurementIndex() === $this) {
                $surveyQuestion->setMeasurementIndex(null);
            }
        }

        return $this;
    }
}
