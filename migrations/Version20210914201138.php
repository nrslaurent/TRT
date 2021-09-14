<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210914201138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobs ADD created_by_id INT NOT NULL, ADD checked_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC5B03A8386 FOREIGN KEY (created_by_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE jobs ADD CONSTRAINT FK_A8936DC52199DB86 FOREIGN KEY (checked_by_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_A8936DC5B03A8386 ON jobs (created_by_id)');
        $this->addSql('CREATE INDEX IDX_A8936DC52199DB86 ON jobs (checked_by_id)');
        $this->addSql('ALTER TABLE users ADD company_id INT DEFAULT NULL, ADD validated_by_id INT DEFAULT NULL, ADD validated TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9C69DE5E5 FOREIGN KEY (validated_by_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9979B1AD6 ON users (company_id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9C69DE5E5 ON users (validated_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC5B03A8386');
        $this->addSql('ALTER TABLE jobs DROP FOREIGN KEY FK_A8936DC52199DB86');
        $this->addSql('DROP INDEX IDX_A8936DC5B03A8386 ON jobs');
        $this->addSql('DROP INDEX IDX_A8936DC52199DB86 ON jobs');
        $this->addSql('ALTER TABLE jobs DROP created_by_id, DROP checked_by_id');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9979B1AD6');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9C69DE5E5');
        $this->addSql('DROP INDEX IDX_1483A5E9979B1AD6 ON users');
        $this->addSql('DROP INDEX IDX_1483A5E9C69DE5E5 ON users');
        $this->addSql('ALTER TABLE users DROP company_id, DROP validated_by_id, DROP validated');
    }
}
