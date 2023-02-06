<?php

namespace App\Entity;

use App\Repository\SurveySubmitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SurveySubmitRepository::class)
 */
class SurveySubmit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MeasurementIndex::class, inversedBy="surveySubmits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $measurementIndex;

    /**
     * @ORM\ManyToOne(targetEntity=SonataUserUser::class)
     */
    private $submittedBy;

    /**
     * @ORM\Column(type="json_document", options={"jsonb":true})
     */
    private $submittedData;
 
    public function getId(): ?int
    {
        return $this->id;
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

    public function getSubmittedBy(): ?SonataUserUser
    {
        return $this->submittedBy;
    }

    public function setSubmittedBy(?SonataUserUser $submittedBy): self
    {
        $this->submittedBy = $submittedBy;

        return $this;
    }

    /**
     * Get the value of submittedData
     */ 
    public function getSubmittedData()
    {
        return $this->submittedData;
    }

    /**
     * Set the value of submittedData
     *
     * @return  self
     */ 
    public function setSubmittedData($submittedData)
    {
        $this->submittedData = $submittedData;

        return $this;
    }
}
