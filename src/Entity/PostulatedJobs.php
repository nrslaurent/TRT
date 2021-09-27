<?php

namespace App\Entity;

use App\Repository\PostulatedJobsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostulatedJobsRepository::class)
 */
class PostulatedJobs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Jobs::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $job;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidate;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class)
     */
    private $checkedBy;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $validated;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJob(): ?Jobs
    {
        return $this->job;
    }

    public function setJob(?Jobs $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getCandidate(): ?Users
    {
        return $this->candidate;
    }

    public function setCandidate(?Users $candidate): self
    {
        $this->candidate = $candidate;

        return $this;
    }

    public function getCheckedBy(): ?Users
    {
        return $this->checkedBy;
    }

    public function setCheckedBy(?Users $checkedBy): self
    {
        $this->checkedBy = $checkedBy;

        return $this;
    }

    public function getValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(?bool $validated): self
    {
        $this->validated = $validated;

        return $this;
    }
}
