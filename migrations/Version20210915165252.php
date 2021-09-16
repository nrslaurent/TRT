<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210915165252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jobs_users (jobs_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_C6A2371C48704627 (jobs_id), INDEX IDX_C6A2371C67B3B43D (users_id), PRIMARY KEY(jobs_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jobs_users ADD CONSTRAINT FK_C6A2371C48704627 FOREIGN KEY (jobs_id) REFERENCES jobs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jobs_users ADD CONSTRAINT FK_C6A2371C67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE jobs_users');
    }
}
