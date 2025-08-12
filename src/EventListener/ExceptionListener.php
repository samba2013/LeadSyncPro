<?php
namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

class ExceptionListener
{
    public function __construct(private readonly LoggerInterface $logger) {
       
    }
    public function __invoke(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        $data = [
                "status"=>"Error",
                "message"=>"Unexpected internal error occured while processing your request, please check back later."
        ];
        
        
        $response = new Response(status: Response::HTTP_INTERNAL_SERVER_ERROR);
        $this->logger->error((string)$exception);
       

        if ($exception instanceof HttpExceptionInterface) {
            $data['status']="Invalid";
            $data['message']=$exception->getMessage();
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());

        }
       

         // Customize your response object to display the exception details
        $content = json_encode($data);
        $response->setContent($content);

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}