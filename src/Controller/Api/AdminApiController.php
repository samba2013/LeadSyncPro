<?php

namespace App\Controller\Api;

use App\Entity\AppUser;
use App\Entity\AppUserApiKey;
use App\Model\AppUserApiKeyCreateDto;
use App\Model\AppUserCreateDto;
use App\Model\AppUserUpdateDto;
use App\Model\AppUserViewDto;
use App\Repository\Enums\ExceptionCodeEnum;
use App\Repository\Interfaces\AppUserApiKeyRepositoryInterface;
use App\Repository\Interfaces\AppUserRepositoryInterface;
use App\Resolver\PatchRequestPayloadResolver;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin/api/user/")]
final class AdminApiController extends AbstractController
{

    public function __construct(
        private readonly AppUserRepositoryInterface $appUserRepository,
        private readonly AppUserApiKeyRepositoryInterface $appUserApiKeyRepository,
        private readonly LoggerInterface $logger
    )
    {
        
    }

    #[Route('', name: 'app_get_users_api', methods:["GET"], format: "json")]
    public function getAllUsers(#[MapQueryParameter] bool $include_access_tokens = false):JsonResponse
    {
        $users = $include_access_tokens ? $this->appUserRepository->getAppUsers() : $this->appUserRepository->findAllWithoutApiTokens();
        return $this->json($users,Response::HTTP_OK);
    }

    #[Route('{id}', name: 'app_get_user_api', methods:["GET"], format: "json")]
    public function findUser(int $id):Response
    {
        $user = $this->appUserRepository->findAppUserById($id);
        if(!$user){
            return $this->json(null,Response::HTTP_NOT_FOUND);
        }
        
        return $this->json(AppUserViewDto::getInstanceFromAppUser($user),Response::HTTP_OK);
    }

    #[Route('', name: 'app_register_user_api', methods:["POST"], format: "json")]
    public function registerAppUser(#[MapRequestPayload(
        acceptFormat:"json",
        validationFailedStatusCode:Response::HTTP_BAD_REQUEST
    )] AppUserCreateDto $userCreateDto):Response
    {
        try {
            $user = (new AppUser($userCreateDto->email))
                ->setFullName($userCreateDto->fullName)
                ->setDescription($userCreateDto->description)
                ->setSourceUrl($userCreateDto->sourceUrl)
                ->setRoles($userCreateDto->roles)
                ->setIsActive(1);
            $this->appUserRepository->addAppUser($user);
            $this->appUserRepository->saveChanges();
           
            return $this->redirectToRoute('app_get_user_api', ['id' => $user->getId()],status:Response::HTTP_MOVED_PERMANENTLY);
        } catch (\Throwable $th) {
            $message = "Unexpected error occured while creating app user, check the logs for details.";
            $status = "Error";
            $responseStatusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            if($th->getCode() == ExceptionCodeEnum::DUPLICATE_APP_USER->value){
                $status = "Unprocessable";
                $message = "Email already exists, please use different email.";
                $responseStatusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
            }
            $this->logger->error((string)$th);
            return $this->json([
                'status'=>$status,
                'message'=>$message
            ],$responseStatusCode);
        }
        
    }

    #[Route('{id}', name: 'app_update_user_api', methods:["PATCH","PUT"], format: "json")]
    public function updateAppUser(int $id, #[MapRequestPayload(
        acceptFormat:"json",
        validationFailedStatusCode:Response::HTTP_BAD_REQUEST,
    )] AppUserUpdateDto $userUpdateDto):Response
    {
        $user = $this->appUserRepository->findAppUserById($id);
        if(!$user)
            return $this->json([
                    "status"=>"Not found",
                    "message"=>"Resource requested is not found."
                ],Response::HTTP_NOT_FOUND);

        $status_code = Response::HTTP_NO_CONTENT;
        $data = null;
        try {
         
            $this->appUserRepository->updateAppUser($user,$userUpdateDto);
            $this->appUserRepository->saveChanges();
           

        } catch (\Throwable $th) {
            $this->logger->error((string)$th);
            $data=[
                "status"=>"Error",
                "message"=>"Unexpected error occured while creating app user, check the logs for details."
            ];
            $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }
        return $this->json($data,$status_code);
    }


    #[Route('{id}', name: 'app_delete_user_api', methods:["DELETE"], format: "json")]
    public function deleteAppUser(int $id):Response
    {
        $user = $this->appUserRepository->findAppUserById($id);
        if(!$user)
            return $this->json([
                    "status"=>"Not found",
                    "message"=>"Resource requested is not found."
                ],Response::HTTP_NOT_FOUND);
        try {
            $this->appUserRepository->deleteAppUser($user);
            $this->appUserRepository->saveChanges();

        } catch (\Throwable $th) {
            $this->logger->error((string)$th);
            return $this->json([
                "status"=>"Error",
                "message"=>"Unexpected error occured while creating app user, check the logs for details."
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->json(null,Response::HTTP_NO_CONTENT);
    }



    #[Route('{id}/access_tokens', name: 'app_user_access_token_api', methods:["POST"], format: "json")]
    public function getActiveAccessTokenForUser(
        int $id,
        #[MapRequestPayload(
        acceptFormat:"json",
        validationFailedStatusCode:Response::HTTP_BAD_REQUEST
    )] AppUserApiKeyCreateDto $apiKeyCreateDto
        ):Response
    {

        $user = $this->appUserRepository->findAppUserById($id);
        if(!$user || !$user->isActive())
            return $this->json([
                    "status"=>"Not found",
                    "message"=>"Resource requested is not found."
                ],Response::HTTP_NOT_FOUND);
        $apiTokenFresh = null;
        try {
            $apiTokenFresh = AppUserApiKey::getFreshApiToken();
            $expiresAt  = $apiKeyCreateDto->expirationDate ? new DateTimeImmutable($apiKeyCreateDto->expirationDate):null;
            $apiTokenResource = new AppUserApiKey(AppUserApiKey::hashApiToken($apiTokenFresh),$expiresAt);
            $apiTokenResource->setAppUser($user);
            $this->appUserApiKeyRepository->createUserApiKey(
                $apiTokenResource
            );
            $this->appUserApiKeyRepository->saveChanges();

        } catch (\Throwable $th) {
            //throw $th;
             return $this->json([
                "status"=>"Error",
                "message"=>"Unexpected error occured while creating app user, check the logs for details."
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
       
            return $this->json([
                "status"=>"Ok",
                "api_token"=>$apiTokenFresh,
                "expires_at"=>$apiTokenResource?->getExpiresAt()?->format("Y-m-d H:i:s")
            ],Response::HTTP_CREATED);
    }
}
