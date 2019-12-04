<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191204135706 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reservation CHANGE verification_key verification_key VARCHAR(255) DEFAULT NULL, CHANGE verification_key_expiration_date verification_key_expiration_date DATETIME DEFAULT NULL, CHANGE is_cancelled is_cancelled TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE contractor ADD title VARCHAR(32) DEFAULT NULL, ADD address VARCHAR(64) DEFAULT NULL, ADD facebook VARCHAR(32) DEFAULT NULL, ADD cover_photo_filename VARCHAR(255) DEFAULT NULL, ADD profile_photo_filename VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE phone_number phone_number VARCHAR(16) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contractor DROP title, DROP address, DROP facebook, DROP cover_photo_filename, DROP profile_photo_filename, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE phone_number phone_number VARCHAR(16) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reservation CHANGE verification_key verification_key VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE verification_key_expiration_date verification_key_expiration_date DATETIME DEFAULT \'NULL\', CHANGE is_cancelled is_cancelled TINYINT(1) DEFAULT \'NULL\'');
    }
}
