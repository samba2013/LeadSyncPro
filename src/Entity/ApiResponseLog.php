<?php

namespace App\Entity;

use App\Repository\ApiResponseLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;

#[ORM\Entity(repositoryClass: ApiResponseLogRepository::class)]
#[Table("api_response_logs")]
class ApiResponseLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'apiResponseLog', cascade: ['persist', 'remove'])]
    private ?ApiRequestLog $api_request = null;

    #[ORM\Column]
    private ?int $status_code = null;

    #[ORM\Column(nullable: true,type: Types::TEXT)]
    private ?string $response_body = null;

    #[ORM\Column(type: Types::TEXT)]
    private string $metadata;

    #[ORM\Column]
    private ?\DateTimeImmutable $sent_at = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatusCode(): ?int
    {
        return $this->status_code;
    }

    public function setStatusCode(int $status_code): static
    {
        $this->status_code = $status_code;

        return $this;
    }

    public function getResponseBody(): ?array
    {
        if($this->response_body)
            try {
                return json_decode(base64_decode($this->response_body),true);
            } catch (\Throwable $th) {
                //throw $th;
            }
        
        return null;
    }

    public function setResponseBody(?array $response_body): static
    {
        $this->response_body = $response_body ? base64_encode(json_encode($response_body)): null;

        return $this;
    }

    public function getMetadata(): array
    {
            try {
                return json_decode(base64_decode($this->metadata),true);
            } catch (\Throwable $th) {
                //throw $th;
            }

        return [];
    }

    public function setMetadata(array $metadata): static
    {
        $this->metadata = base64_encode(json_encode($metadata));

        return $this;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sent_at;
    }

    public function setSentAt(\DateTimeImmutable $sent_at): static
    {
        $this->sent_at = $sent_at;

        return $this;
    }
}
