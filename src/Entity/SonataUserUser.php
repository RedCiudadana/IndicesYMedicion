<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="sonata_user__user")
 */
class SonataUserUser extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity=MeasurementIndex::class, mappedBy="researchers")
     */
    private $measurementIndices;

    public function __construct()
    {
        $this->measurementIndices = new ArrayCollection();
    }

    /**
     * @return Collection<int, MeasurementIndex>
     */
    public function getMeasurementIndices(): Collection
    {
        return $this->measurementIndices;
    }

    public function addMeasurementIndex(MeasurementIndex $measurementIndex): self
    {
        if (!$this->measurementIndices->contains($measurementIndex)) {
            $this->measurementIndices[] = $measurementIndex;
            $measurementIndex->addResearcher($this);
        }

        return $this;
    }

    public function removeMeasurementIndex(MeasurementIndex $measurementIndex): self
    {
        if ($this->measurementIndices->removeElement($measurementIndex)) {
            $measurementIndex->removeResearcher($this);
        }

        return $this;
    }
}