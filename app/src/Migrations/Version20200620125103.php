<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200620125103 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE borrowings CHANGE record_id record_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE users_data ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users_data ADD CONSTRAINT FK_627ABD6D67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_627ABD6D67B3B43D ON users_data (users_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE borrowings CHANGE record_id record_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE users_data DROP FOREIGN KEY FK_627ABD6D67B3B43D');
        $this->addSql('DROP INDEX UNIQ_627ABD6D67B3B43D ON users_data');
        $this->addSql('ALTER TABLE users_data DROP users_id');
    }
}
