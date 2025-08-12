<?php

namespace App\Controller\Api;

use App\Entity\AppUser;
use App\Message\StoreLeadMessage;
use App\Model\LeadCreateDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class TrackingApiController extends AbstractController
{

    public function __construct(
        private readonly MessageBusInterface $bus
    )
    {
        
    }
    #[Route('/api/tracking/', name: 'app_tracking_api', methods:["POST"], format: "json")]
    public function index(
        Request $request,
        #[CurrentUser] ?AppUser $appUser,
        #[MapRequestPayload(
        acceptFormat: 'json',
        // validationGroups: ['strict', 'read'],
        validationFailedStatusCode: Response::HTTP_BAD_REQUEST
    )] LeadCreateDto $leadDto
    ): JsonResponse
    {

        $apiRquestLogId = $request->attributes->get('api_request_log_id') ?? null;
        $this->bus->dispatch(new StoreLeadMessage($leadDto,$apiRquestLogId));

       

        return $this->json([
            'status' => 'Ok',
            'message' => 'Data has been successfully saved',
        ],Response::HTTP_CREATED);
    }
}
