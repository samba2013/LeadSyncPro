<?php

namespace App\Command;

use App\Entity\ApiClient;
use App\Entity\AppUser;
use App\Entity\AppUserApiKey;
use App\Repository\Interfaces\AppUserRepositoryInterface;
use App\Repository\Interfaces\IApiClientRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-client-apikey',
    description: 'Create new client api key',
)]
class CreateClientApikeyCommand extends Command
{
    public function __construct(
        private readonly AppUserRepositoryInterface $appUserRepository
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Client email')
            ->addOption('expires-after-days', null, InputOption::VALUE_OPTIONAL, 'Set expiration after days')
            ->addOption('full_name', null, InputOption::VALUE_OPTIONAL, 'Set name');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $expiresAfterDays = $input->getOption('expires-after-days');
        $clientName = $input->getOption('full_name');
        $appUserInstance = $this->appUserRepository->findAppUserByEmail($email) ?? new AppUser($email);
        

        $expiresAfterMsg = "";
        $expiresAt = null;
        if ($expiresAfterDays) {
           $expiresAt = (new \DateTimeImmutable())->modify('+'.$expiresAfterDays.' days');
           $expiresAfterMsg = "valid till ".$expiresAt->format("u");
        }
        if ($clientName) {
           $appUserInstance->setFullName($clientName);
        }

        if(!$appUserInstance->isActive()){
            $appUserInstance->setIsActive(true);
        }

        $appUserInstance->setRoles(["ROLE_API_CLIENT"]);

        $newApiKey = bin2hex(random_bytes(32)); // 64
        $appUserApiKey = new AppUserApiKey(hash("sha256",$newApiKey),$expiresAt);
        $appUserInstance->addApiToken($appUserApiKey);

        $io->success("Your api key is : ".$newApiKey.$expiresAfterMsg);
        $this->appUserRepository->addAppUser($appUserInstance);
        $this->appUserRepository->saveChanges();

        return Command::SUCCESS;
    }
}
