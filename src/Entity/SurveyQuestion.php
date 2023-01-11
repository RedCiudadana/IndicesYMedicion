<?php

// Una pregunta tiene

// label

// descripion

// formType (clases configuradas de los tipos configurados dentro del sistema)
// formOptions (dependiente de formType)

namespace App\Entity;

use App\Repository\SurveyQuestionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SurveyQuestionRepository::class)
 */
class SurveyQuestion
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
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $formType;

    /**
     * @ORM\Column(type="json")
     */
    private $formOptions = [];

    /**
     * @ORM\ManyToOne(targetEntity=MeasurementIndex::class, inversedBy="surveyQuestions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $measurementIndex;

    public function __toString()
    {
        return $this->name;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFormType(): ?string
    {
        return $this->formType;
    }

    public function setFormType(string $formType): self
    {
        $this->formType = $formType;

        return $this;
    }

    public function getFormOptions(): ?array
    {
        return $this->formOptions;
    }

    public function setFormOptions(array $formOptions): self
    {
        $this->formOptions = $formOptions;

        return $this;
    }

    public function getMeasurementIndex(): ?MeasurementIndex
    {
        return $this->measurementIndex;
    }

    public function setMeasurementIndex(?MeasurementIndex $measurementIndex): self
    {
        $this->measurementIndex = $measurementIndex;

        return $this;
    }
}
