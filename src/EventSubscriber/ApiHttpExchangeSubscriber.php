<?php

namespace App\EventSubscriber;

use App\Entity\ApiRequestLog;
use App\Entity\ApiResponseLog;
use App\Entity\AppUser;
use App\Repository\Interfaces\ApiRequestLogRepositoryInterface;
use App\Repository\Interfaces\ApiResponseLogRepositoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Bundle\SecurityBundle\Security;

class ApiHttpExchangeSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private readonly ApiRequestLogRepositoryInterface $apiReqRepo,
        private readonly ApiResponseLogRepositoryInterface $apiResRepo,
        private readonly Security $security
    ){

    }

    public function onRequestEvent(RequestEvent $event): void
    {
            if (!str_starts_with($event->getRequest()->getPathInfo(), '/api')) {
                return;
            }

            $user = $this->security->getUser();
            if(is_a($user,AppUser::class)){
                $request = $event->getRequest();

                $log = new ApiRequestLog();
                $log->setEndpoint($request->getPathInfo());
                $log->setMethod($request->getMethod());
                $log->setPayload(json_decode($request->getContent(), true));
                $log->setIpAddress($request->getClientIp());
                $log->setReceivedAt(new \DateTimeImmutable());
                $log->setMetadata($request->headers->all());
                $log->setRequestedBy($user);
                $this->apiReqRepo->addApiRequestLog($log);
                $this->apiReqRepo->saveChanges();

                $request->attributes->set('api_request_log_id', $log->getId());
            }
            
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST  => 'onRequestEvent',
            KernelEvents::RESPONSE => 'onResponseEvent',
        ];

        
    }

    public function onResponseEvent(ResponseEvent $event): void
    {
        $request = $event->getRequest();
        $requestId = $request->attributes->get('api_request_log_id');

        
        if ($requestId) {
            $apiResponseLog = $this->apiResRepo->findApiResponseLogById($requestId);
            $log = new ApiResponseLog();
            $log->setApiRequest($apiResponseLog);
            $log->setStatusCode($event->getResponse()->getStatusCode());
            $log->setResponseBody(json_decode($event->getResponse()->getContent(), true));
            $log->setMetadata($event->getRequest()->headers->all());
            $log->setSentAt(new \DateTimeImmutable());
            $this->apiResRepo->addApiResponseLog($log);
            $this->apiReqRepo->saveChanges();
        }
    }
}
