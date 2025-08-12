<?php

namespace App\Entity;

use App\Repository\AppUserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: AppUserRepository::class)]
#[Table("app_users")]
class AppUser implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique:true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $full_name = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?bool $is_active = true;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $source_url = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

     /**
     * @var Collection<int, ApiRequestLog>
     */
    #[ORM\OneToMany(targetEntity: ApiRequestLog::class, mappedBy: 'requested_by')]
    private Collection $api_requests;

    /**
     * @var Collection<int, AppUserApiKey>
     */
    #[ORM\OneToMany(targetEntity: AppUserApiKey::class, mappedBy: 'appUser', cascade: ['persist'])]
    private Collection $api_tokens;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deleted_at = null;

    public function __construct(string $email)
    {
        $this->email = $email;
        $this->is_active = 1; // we can define letter a logic when we confirm email then we can set active to 1
        $this->api_tokens = new ArrayCollection();
        $this->api_requests = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail() : string {
        return $this->email;   
    }

    public function setEmail(string $email):static
    {
        $this->email = $email;
        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): static
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSourceUrl(): ?string
    {
        return $this->source_url;
    }

    public function setSourceUrl(?string $source_url): static
    {
        $this->source_url = $source_url;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, ApiClient>
     */
    public function getApiTokens(): Collection
    {
        return $this->api_tokens;
    }

    public function addApiToken(AppUserApiKey $apiToken): static
    {
        if (!$this->api_tokens->contains($apiToken)) {
            $this->api_tokens->add($apiToken);
            $apiToken->setAppUser($this);
        }

        return $this;
    }

    public function removeApiToken(AppUserApiKey $apiToken): static
    {
        if ($this->api_tokens->removeElement($apiToken)) {
            // set the owning side to null (unless already changed)
            if ($apiToken->getAppUser() === $this) {
                $apiToken->setAppUser(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier(): string
     {
        return (string) $this->email;
     }

     /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, ApiRequestLog>
     */
    public function getApiRequests(): Collection
    {
        return $this->api_requests;
    }

    public function addApiRequest(ApiRequestLog $apiRequest): static
    {
        if (!$this->api_requests->contains($apiRequest)) {
            $this->api_requests->add($apiRequest);
            $apiRequest->setRequestedBy($this);
        }

        return $this;
    }

    public function removeApiRequest(ApiRequestLog $apiRequest): static
    {
        if ($this->api_requests->removeElement($apiRequest)) {
            // set the owning side to null (unless already changed)
            if ($apiRequest->getRequestedBy() === $this) {
                $apiRequest->setRequestedBy(null);
            }
        }

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?\DateTimeImmutable $deleted_at): static
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }
}
