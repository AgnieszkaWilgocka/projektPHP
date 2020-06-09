<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200608220146 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE borrowings ADD record_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE borrowings ADD CONSTRAINT FK_7547A7B44DFD750C FOREIGN KEY (record_id) REFERENCES records (id)');
        $this->addSql('CREATE INDEX IDX_7547A7B44DFD750C ON borrowings (record_id)');
        $this->addSql('ALTER TABLE users CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE borrowings DROP FOREIGN KEY FK_7547A7B44DFD750C');
        $this->addSql('DROP INDEX IDX_7547A7B44DFD750C ON borrowings');
        $this->addSql('ALTER TABLE borrowings DROP record_id');
        $this->addSql('ALTER TABLE users CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
