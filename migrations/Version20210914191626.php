<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210914191626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9979B1AD6');
        $this->addSql('DROP TABLE companies');
        $this->addSql('DROP TABLE jobs');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9CF1776D6');
        $this->addSql('DROP INDEX IDX_1483A5E9979B1AD6 ON users');
        $this->addSql('DROP INDEX IDX_1483A5E9CF1776D6 ON users');
        $this->addSql('ALTER TABLE users ADD check_by VARCHAR(255) DEFAULT NULL, DROP company_id, DROP user_checked_by_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE companies (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, address VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_8244AA3AB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE jobs (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, checked_by_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, address VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, hours VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, salary VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, published TINYINT(1) DEFAULT NULL, INDEX IDX_A8936DC52199DB86 (checked_by_id), INDEX IDX_A8936DC5B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE companies ADD CONSTRAINT FK_8244AA3AB03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC52199DB86 FOREIGN KEY (checked_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC5B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD company_id INT DEFAULT NULL, ADD user_checked_by_id INT DEFAULT NULL, DROP check_by');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9CF1776D6 FOREIGN KEY (user_checked_by_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9979B1AD6 ON users (company_id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9CF1776D6 ON users (user_checked_by_id)');
    }
}
