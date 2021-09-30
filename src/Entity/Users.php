<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\ManyToOne(targetEntity=Companies::class, inversedBy="users")
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity=Jobs::class, mappedBy="createdBy", orphanRemoval=true)
     */
    private $jobsCreated;

    /**
     * @ORM\OneToMany(targetEntity=Jobs::class, mappedBy="checkedBy")
     */
    private $jobsChecked;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Validated;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="usersValidated")
     */
    private $ValidatedBy;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="ValidatedBy")
     */
    private $usersValidated;

    /**
     * @ORM\ManyToMany(targetEntity=Jobs::class, mappedBy="Candidates")
     */
    private $jobsPostulated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cv;


    public function __construct()
    {
        $this->jobsCreated = new ArrayCollection();
        $this->jobsChecked = new ArrayCollection();
        $this->usersValidated = new ArrayCollection();
        $this->jobsPostulated = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCompany(): ?Companies
    {
        return $this->company;
    }

    public function setCompany(?Companies $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection|Jobs[]
     */
    public function getJobsCreated(): Collection
    {
        return $this->jobsCreated;
    }

    public function addJobsCreated(Jobs $jobsCreated): self
    {
        if (!$this->jobsCreated->contains($jobsCreated)) {
            $this->jobsCreated[] = $jobsCreated;
            $jobsCreated->setCreatedBy($this);
        }

        return $this;
    }

    public function removeJobsCreated(Jobs $jobsCreated): self
    {
        if ($this->jobsCreated->removeElement($jobsCreated)) {
            // set the owning side to null (unless already changed)
            if ($jobsCreated->getCreatedBy() === $this) {
                $jobsCreated->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Jobs[]
     */
    public function getJobsChecked(): Collection
    {
        return $this->jobsChecked;
    }

    public function addJobsChecked(Jobs $jobsChecked): self
    {
        if (!$this->jobsChecked->contains($jobsChecked)) {
            $this->jobsChecked[] = $jobsChecked;
            $jobsChecked->setCheckedBy($this);
        }

        return $this;
    }

    public function removeJobsChecked(Jobs $jobsChecked): self
    {
        if ($this->jobsChecked->removeElement($jobsChecked)) {
            // set the owning side to null (unless already changed)
            if ($jobsChecked->getCheckedBy() === $this) {
                $jobsChecked->setCheckedBy(null);
            }
        }

        return $this;
    }

    public function getValidated(): ?bool
    {
        return $this->Validated;
    }

    public function setValidated(bool $Validated): self
    {
        $this->Validated = $Validated;

        return $this;
    }

    public function getValidatedBy(): ?self
    {
        return $this->ValidatedBy;
    }

    public function setValidatedBy(?self $ValidatedBy): self
    {
        $this->ValidatedBy = $ValidatedBy;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getUsersValidated(): Collection
    {
        return $this->usersValidated;
    }

    public function addUsersValidated(self $usersValidated): self
    {
        if (!$this->usersValidated->contains($usersValidated)) {
            $this->usersValidated[] = $usersValidated;
            $usersValidated->setValidatedBy($this);
        }

        return $this;
    }

    public function removeUsersValidated(self $usersValidated): self
    {
        if ($this->usersValidated->removeElement($usersValidated)) {
            // set the owning side to null (unless already changed)
            if ($usersValidated->getValidatedBy() === $this) {
                $usersValidated->setValidatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Jobs[]
     */
    public function getJobsPostulated(): Collection
    {
        return $this->jobsPostulated;
    }

    public function addJobsPostulated(Jobs $jobsPostulated): self
    {
        if (!$this->jobsPostulated->contains($jobsPostulated)) {
            $this->jobsPostulated[] = $jobsPostulated;
            $jobsPostulated->addCandidate($this);
        }

        return $this;
    }

    public function removeJobsPostulated(Jobs $jobsPostulated): self
    {
        if ($this->jobsPostulated->removeElement($jobsPostulated)) {
            $jobsPostulated->removeCandidate($this);
        }

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }
}
