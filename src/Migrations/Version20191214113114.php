<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191214113114 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE service_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_type_contractor (service_type_id INT NOT NULL, contractor_id INT NOT NULL, INDEX IDX_7C79742EAC8DE0F (service_type_id), INDEX IDX_7C79742EB0265DC7 (contractor_id), PRIMARY KEY(service_type_id, contractor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service_type_contractor ADD CONSTRAINT FK_7C79742EAC8DE0F FOREIGN KEY (service_type_id) REFERENCES service_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE service_type_contractor ADD CONSTRAINT FK_7C79742EB0265DC7 FOREIGN KEY (contractor_id) REFERENCES contractor (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE lost_password');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE contractor CHANGE roles roles JSON NOT NULL, CHANGE phone_number phone_number VARCHAR(16) DEFAULT NULL, CHANGE title title VARCHAR(32) DEFAULT NULL, CHANGE address address VARCHAR(64) DEFAULT NULL, CHANGE facebook facebook VARCHAR(32) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE verification_key verification_key VARCHAR(255) DEFAULT NULL, CHANGE verification_key_expiration_date verification_key_expiration_date DATETIME DEFAULT NULL, CHANGE is_cancelled is_cancelled TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE cover_photo CHANGE filename filename VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE profile_photo CHANGE filename filename VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE service_type_contractor DROP FOREIGN KEY FK_7C79742EAC8DE0F');
        $this->addSql('CREATE TABLE lost_password (id INT AUTO_INCREMENT NOT NULL, contractor_id INT NOT NULL, reset_key VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, expires_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_A492D055B0265DC7 (contractor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE lost_password ADD CONSTRAINT FK_A492D055B0265DC7 FOREIGN KEY (contractor_id) REFERENCES contractor (id)');
        $this->addSql('DROP TABLE service_type');
        $this->addSql('DROP TABLE service_type_contractor');
        $this->addSql('ALTER TABLE contractor CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE phone_number phone_number VARCHAR(16) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE title title VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE address address VARCHAR(64) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE facebook facebook VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cover_photo CHANGE filename filename VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE profile_photo CHANGE filename filename VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reservation CHANGE verification_key verification_key VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE verification_key_expiration_date verification_key_expiration_date DATETIME DEFAULT \'NULL\', CHANGE is_cancelled is_cancelled TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
