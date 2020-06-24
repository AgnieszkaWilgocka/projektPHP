<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200623114650 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE borrowings ADD status_id INT DEFAULT NULL, CHANGE record_id record_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE borrowings ADD CONSTRAINT FK_7547A7B46BF700BD FOREIGN KEY (status_id) REFERENCES borrowings_status (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7547A7B46BF700BD ON borrowings (status_id)');
        $this->addSql('ALTER TABLE borrowings_status DROP FOREIGN KEY FK_E4221C8D4675F064');
        $this->addSql('DROP INDEX UNIQ_E4221C8D4675F064 ON borrowings_status');
        $this->addSql('ALTER TABLE borrowings_status DROP borrowing_id');
        $this->addSql('ALTER TABLE users CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE users_data CHANGE users_id users_id INT DEFAULT NULL, CHANGE nick nick VARCHAR(45) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE borrowings DROP FOREIGN KEY FK_7547A7B46BF700BD');
        $this->addSql('DROP INDEX UNIQ_7547A7B46BF700BD ON borrowings');
        $this->addSql('ALTER TABLE borrowings DROP status_id, CHANGE record_id record_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE borrowings_status ADD borrowing_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE borrowings_status ADD CONSTRAINT FK_E4221C8D4675F064 FOREIGN KEY (borrowing_id) REFERENCES borrowings (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E4221C8D4675F064 ON borrowings_status (borrowing_id)');
        $this->addSql('ALTER TABLE users CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE users_data CHANGE users_id users_id INT DEFAULT NULL, CHANGE nick nick VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
