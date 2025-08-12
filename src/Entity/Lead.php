<?php

namespace App\Entity;

use App\Model\LeadCreateDto;
use App\Repository\LeadRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeadRepository::class)]
#[ORM\Table(name: '`leads`')]
class Lead
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $first_name = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255, unique:true)]
    private ?string $email = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dob = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(nullable: true)]
    private ?string $zip_code = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $countryCode = null;

    #[ORM\Column(length: 2, nullable: true)]
    private ?string $provinceCode = null;

    #[ORM\OneToOne(inversedBy: 'lead', cascade: ['persist', 'remove'])]
    private ?ApiRequestLog $api_request = null;

    #[ORM\Column]
    private ?array $verticals = [];

    #[ORM\Column(nullable: true)]
    private ?array $extra_data = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

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

    public function getDob(): ?\DateTime
    {
        return $this->dob;
    }

    public function setDob(?\DateTime $dob): static
    {
        $this->dob = $dob;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function setZipCode(?string $zip_code): static
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getProvinceCode(): ?string
    {
        return $this->provinceCode;
    }

    public function setProvinceCode(?string $provinceCode): static
    {
        $this->provinceCode = $provinceCode;

        return $this;
    }

    static function fromCreateDto(LeadCreateDto $leadCreateDto){
        $lead = new static();
        $lead->setFirstName($leadCreateDto->firstName);
        $lead->setLastName($leadCreateDto->lastName);
        $lead->setEmail($leadCreateDto->email);
        $lead->setCity($leadCreateDto->city);
        $lead->setCountryCode($leadCreateDto->countryCode);
        $lead->setProvinceCode($leadCreateDto->provinceCode);
        $lead->setZipCode($leadCreateDto->zipCode);
        $lead->setDob($leadCreateDto->dob ? new DateTime($leadCreateDto->dob) : null);
        $lead->setVerticals($leadCreateDto->verticals);
        $lead->setPhone($leadCreateDto->phone);
        $lead->setExtraData($leadCreateDto->extraData);
        $lead->setCreatedAt(new DateTimeImmutable());
        return $lead;
    }

    public function getApiRequest(): ?ApiRequestLog
    {
        return $this->api_request;
    }

    public function setApiRequest(?ApiRequestLog $api_request): static
    {
        $this->api_request = $api_request;

        return $this;
    }

    public function getVerticals(): ?string
    {
        return $this->verticals;
    }

    public function setVerticals(array $verticals): static
    {
        $this->verticals = $verticals;

        return $this;
    }

    public function getExtraData(): ?array
    {
        return $this->extra_data;
    }

    public function setExtraData(?array $extra_data): static
    {
        $this->extra_data = $extra_data;

        return $this;
    }
}
