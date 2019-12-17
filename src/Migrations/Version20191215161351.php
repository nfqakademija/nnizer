<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191215161351 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, contractor_id INT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, visit_date DATETIME NOT NULL, is_verified TINYINT(1) NOT NULL, verification_key VARCHAR(255) DEFAULT NULL, verification_key_expiration_date DATETIME DEFAULT NULL, is_cancelled TINYINT(1) DEFAULT NULL, is_completed TINYINT(1) NOT NULL, is_deleted TINYINT(1) DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_42C84955B0265DC7 (contractor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lost_password (id INT AUTO_INCREMENT NOT NULL, contractor_id INT NOT NULL, reset_key VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_A492D055B0265DC7 (contractor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contractor (id INT AUTO_INCREMENT NOT NULL, services_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(32) DEFAULT NULL, lastname VARCHAR(32) DEFAULT NULL, email VARCHAR(64) NOT NULL, phone_number VARCHAR(16) DEFAULT NULL, verification_key VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, title VARCHAR(32) DEFAULT NULL, address VARCHAR(64) DEFAULT NULL, facebook VARCHAR(32) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_437BD2EFF85E0677 (username), INDEX IDX_437BD2EFAEF5A6C1 (services_id), INDEX idx_key (verification_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cover_photo (id INT AUTO_INCREMENT NOT NULL, contractor_id INT NOT NULL, filename VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_CD55028DB0265DC7 (contractor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contractor_settings (id INT AUTO_INCREMENT NOT NULL, contractor_id INT NOT NULL, monday VARCHAR(255) NOT NULL, tuesday VARCHAR(255) NOT NULL, wednesday VARCHAR(255) NOT NULL, thursday VARCHAR(255) NOT NULL, friday VARCHAR(255) NOT NULL, saturday VARCHAR(255) NOT NULL, sunday VARCHAR(255) NOT NULL, visit_duration INT NOT NULL, UNIQUE INDEX UNIQ_D9126EDB0265DC7 (contractor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile_photo (id INT AUTO_INCREMENT NOT NULL, contractor_id INT NOT NULL, filename VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_E3631BCAB0265DC7 (contractor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, contractor_id INT NOT NULL, reservation_id INT NOT NULL, stars INT NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_794381C6B0265DC7 (contractor_id), UNIQUE INDEX UNIQ_794381C6B83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955B0265DC7 FOREIGN KEY (contractor_id) REFERENCES contractor (id)');
        $this->addSql('ALTER TABLE lost_password ADD CONSTRAINT FK_A492D055B0265DC7 FOREIGN KEY (contractor_id) REFERENCES contractor (id)');
        $this->addSql('ALTER TABLE contractor ADD CONSTRAINT FK_437BD2EFAEF5A6C1 FOREIGN KEY (services_id) REFERENCES service_type (id)');
        $this->addSql('ALTER TABLE cover_photo ADD CONSTRAINT FK_CD55028DB0265DC7 FOREIGN KEY (contractor_id) REFERENCES contractor (id)');
        $this->addSql('ALTER TABLE contractor_settings ADD CONSTRAINT FK_D9126EDB0265DC7 FOREIGN KEY (contractor_id) REFERENCES contractor (id)');
        $this->addSql('ALTER TABLE profile_photo ADD CONSTRAINT FK_E3631BCAB0265DC7 FOREIGN KEY (contractor_id) REFERENCES contractor (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6B0265DC7 FOREIGN KEY (contractor_id) REFERENCES contractor (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6B83297E7');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955B0265DC7');
        $this->addSql('ALTER TABLE lost_password DROP FOREIGN KEY FK_A492D055B0265DC7');
        $this->addSql('ALTER TABLE cover_photo DROP FOREIGN KEY FK_CD55028DB0265DC7');
        $this->addSql('ALTER TABLE contractor_settings DROP FOREIGN KEY FK_D9126EDB0265DC7');
        $this->addSql('ALTER TABLE profile_photo DROP FOREIGN KEY FK_E3631BCAB0265DC7');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6B0265DC7');
        $this->addSql('ALTER TABLE contractor DROP FOREIGN KEY FK_437BD2EFAEF5A6C1');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE lost_password');
        $this->addSql('DROP TABLE contractor');
        $this->addSql('DROP TABLE cover_photo');
        $this->addSql('DROP TABLE contractor_settings');
        $this->addSql('DROP TABLE service_type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE profile_photo');
        $this->addSql('DROP TABLE review');
    }
}
