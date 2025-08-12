<?php

namespace App\Entity;

use App\Repository\ApiRequestLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity(repositoryClass: ApiRequestLogRepository::class)]
#[Table("api_request_logs")]
class ApiRequestLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $endpoint = null;

    #[ORM\Column(length: 10)]
    private ?string $method = null;

    #[ORM\Column(type: Types::TEXT, nullable:true)]
    private ?string $payload;

    #[ORM\Column(length: 255)]
    private ?string $ip_address = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $received_at = null;

    #[ORM\Column(nullable: true,type: Types::TEXT)]
    private ?string $metadata = null;

    #[ORM\OneToOne(mappedBy: 'api_request', cascade: ['persist', 'remove'])]
    private ?ApiResponseLog $apiResponseLog = null;

    #[ORM\OneToOne(mappedBy: 'api_request', cascade: ['persist', 'remove'])]
    private ?Lead $lead = null;

    #[ORM\ManyToOne(inversedBy: 'api_requests')]
    private ?AppUser $requested_by = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): static
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getPayload(): ?array
    {
        try{
                return json_decode(base64_decode($this->payload),true);
        }catch(\Exception $e){

        }
        return null;
    }

    public function setPayload(?array $payload): static
    {
        $this->payload = $payload ? base64_encode(json_encode($payload)) : null;

        return $this;
    }

    public function getIpAddress(): ?string
    {
        return $this->ip_address;
    }

    public function setIpAddress(string $ip_address): static
    {
        $this->ip_address = $ip_address;

        return $this;
    }

    public function getReceivedAt(): ?\DateTimeImmutable
    {
        return $this->received_at;
    }

    public function setReceivedAt(\DateTimeImmutable $received_at): static
    {
        $this->received_at = $received_at;

        return $this;
    }

    public function getMetadata(): ?array
    {
        if($this->metadata) {
            try{
                    return json_decode(base64_decode($this->metadata),true);
            }catch(\Exception $e){

            }
        }
        
        return null;
    }

    public function setMetadata(?array $metadata): static
    {
        $this->metadata = base64_encode(json_encode($metadata));

        return $this;
    }

    public function getDataLead(): ?Lead
    {
        return $this->lead;
    }

    public function setDataLead(?Lead $lead): static
    {
        // unset the owning side of the relation if necessary
        if ($lead === null && $this->lead !== null) {
            $this->lead->setApiRequest(null);
        }

        // set the owning side of the relation if necessary
        if ($lead !== null && $lead->getApiRequest() !== $this) {
            $lead->setApiRequest($this);
        }

        $this->lead = $lead;

        return $this;
    }

    public function getApiResponseLog(): ?ApiResponseLog
    {
        return $this->apiResponseLog;
    }

    public function setApiResponseLog(?ApiResponseLog $apiResponseLog): static
    {
        // unset the owning side of the relation if necessary
        if ($apiResponseLog === null && $this->apiResponseLog !== null) {
            $this->apiResponseLog->setApiRequest(null);
        }

        // set the owning side of the relation if necessary
        if ($apiResponseLog !== null && $apiResponseLog->getApiRequest() !== $this) {
            $apiResponseLog->setApiRequest($this);
        }

        $this->apiResponseLog = $apiResponseLog;

        return $this;
    }

   
    public function getRequestedBy(): ?AppUser
    {
        return $this->requested_by;
    }

    public function setRequestedBy(?AppUser $requested_by): static
    {
        $this->requested_by = $requested_by;

        return $this;
    }

}
