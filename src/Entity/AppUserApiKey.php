<?php

namespace App\Entity;

use App\Repository\AppUserApiKeyRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity(repositoryClass: AppUserApiKeyRepository::class)]
#[Table("app_user_api_keys")]
#[ORM\HasLifecycleCallbacks]
class AppUserApiKey
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length:64,unique:true)]
    private ?string $api_key = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expires_at = null;

    #[ORM\Column]
    private ?bool $is_active = true;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;


    #[ORM\ManyToOne(inversedBy: 'api_tokens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?AppUser $appUser = null;

    public function __construct(string $api_key,?\DateTimeImmutable $expires_at = null )
    {
        $this->api_key = $api_key;
        $this->expires_at = $expires_at;
        $this->created_at = new DateTimeImmutable();
        
       
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getApiKey(): ?string
    {
        return $this->api_key;
    }

    public function setApiKey(string $api_key): static
    {
        $this->api_key = $api_key;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expires_at;
    }

    public function setExpiresAt(?\DateTimeImmutable $expires_at): static
    {
        $this->expires_at = $expires_at;

        return $this;
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at < new \DateTimeImmutable();
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->created_at = new \DateTimeImmutable();
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

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updated_at = new \DateTimeImmutable();
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }


    public function getAppUser(): ?AppUser
    {
        return $this->appUser;
    }

    public function setAppUser(?AppUser $appUser): static
    {
        $this->appUser = $appUser;

        return $this;
    }

    static function getFreshApiToken():string
    {
        return bin2hex(random_bytes(32));
    }

    static function hashApiToken(string $apiToken):string
    {
        return hash("sha256",$apiToken);
    }
}
