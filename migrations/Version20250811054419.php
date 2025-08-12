<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250811054419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_request_logs (id INT AUTO_INCREMENT NOT NULL, requested_by_id INT DEFAULT NULL, endpoint LONGTEXT NOT NULL, method VARCHAR(10) NOT NULL, payload LONGTEXT NOT NULL, ip_address VARCHAR(255) NOT NULL, received_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', metadata LONGTEXT DEFAULT NULL, INDEX IDX_8542380B4DA1E751 (requested_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE api_response_logs (id INT AUTO_INCREMENT NOT NULL, api_request_id INT DEFAULT NULL, status_code INT NOT NULL, response_body LONGTEXT DEFAULT NULL, metadata LONGTEXT NOT NULL, sent_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_F443794A85D4C4B4 (api_request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user_api_keys (id INT AUTO_INCREMENT NOT NULL, app_user_id INT NOT NULL, api_key VARCHAR(64) NOT NULL, expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_EF89EBB3C912ED9D (api_key), INDEX IDX_EF89EBB34A3353D8 (app_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(100) NOT NULL, full_name VARCHAR(255) NOT NULL, roles JSON NOT NULL, is_active TINYINT(1) NOT NULL, description VARCHAR(255) DEFAULT NULL, source_url LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_C2502824E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `leads` (id INT AUTO_INCREMENT NOT NULL, api_request_id INT DEFAULT NULL, first_name VARCHAR(100) DEFAULT NULL, last_name VARCHAR(100) DEFAULT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', dob DATE DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(255) DEFAULT NULL, country_code VARCHAR(2) DEFAULT NULL, province_code VARCHAR(2) DEFAULT NULL, verticals JSON NOT NULL, UNIQUE INDEX UNIQ_1790455285D4C4B4 (api_request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE api_request_logs ADD CONSTRAINT FK_8542380B4DA1E751 FOREIGN KEY (requested_by_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE api_response_logs ADD CONSTRAINT FK_F443794A85D4C4B4 FOREIGN KEY (api_request_id) REFERENCES api_request_logs (id)');
        $this->addSql('ALTER TABLE app_user_api_keys ADD CONSTRAINT FK_EF89EBB34A3353D8 FOREIGN KEY (app_user_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE `leads` ADD CONSTRAINT FK_1790455285D4C4B4 FOREIGN KEY (api_request_id) REFERENCES api_request_logs (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_request_logs DROP FOREIGN KEY FK_8542380B4DA1E751');
        $this->addSql('ALTER TABLE api_response_logs DROP FOREIGN KEY FK_F443794A85D4C4B4');
        $this->addSql('ALTER TABLE app_user_api_keys DROP FOREIGN KEY FK_EF89EBB34A3353D8');
        $this->addSql('ALTER TABLE `leads` DROP FOREIGN KEY FK_1790455285D4C4B4');
        $this->addSql('DROP TABLE api_request_logs');
        $this->addSql('DROP TABLE api_response_logs');
        $this->addSql('DROP TABLE app_user_api_keys');
        $this->addSql('DROP TABLE app_users');
        $this->addSql('DROP TABLE `leads`');
    }
}
