<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191117194148 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contractor_settings (id INT AUTO_INCREMENT NOT NULL, contractor_id INT NOT NULL, monday VARCHAR(255) NOT NULL, tuesday VARCHAR(255) NOT NULL, wednesday VARCHAR(255) NOT NULL, thursday VARCHAR(255) NOT NULL, friday VARCHAR(255) NOT NULL, saturday VARCHAR(255) NOT NULL, sunday VARCHAR(255) NOT NULL, visit_duration VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', UNIQUE INDEX UNIQ_D9126EDB0265DC7 (contractor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contractor_settings ADD CONSTRAINT FK_D9126EDB0265DC7 FOREIGN KEY (contractor_id) REFERENCES contractor (id)');
        $this->addSql('ALTER TABLE reservation CHANGE verification_key verification_key VARCHAR(255) DEFAULT NULL, CHANGE verification_key_expiration_date verification_key_expiration_date DATETIME DEFAULT NULL, CHANGE is_cancelled is_cancelled TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE contractor CHANGE roles roles JSON NOT NULL, CHANGE phone_number phone_number VARCHAR(16) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE contractor_settings');
        $this->addSql('ALTER TABLE contractor CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE phone_number phone_number VARCHAR(16) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reservation CHANGE verification_key verification_key VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE verification_key_expiration_date verification_key_expiration_date DATETIME DEFAULT \'NULL\', CHANGE is_cancelled is_cancelled TINYINT(1) DEFAULT \'NULL\'');
    }
}
