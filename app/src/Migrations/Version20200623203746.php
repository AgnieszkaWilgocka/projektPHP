<?php

declare(strict_types=1);

/**
 * Doctrine migrations
 */
namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200623203746 extends AbstractMigration
{
    /**
     * Getter for description
     *
     * @return string
     */
    public function getDescription() : string
    {
        return '';
    }

    /**
     * Function up
     *
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE borrowings ADD created_at DATETIME DEFAULT NULL, ADD comment VARCHAR(255) DEFAULT NULL, CHANGE record_id record_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE user_data_id user_data_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE users_data CHANGE nick nick VARCHAR(45) DEFAULT NULL');
    }

    /**
     * Function down
     *
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE borrowings DROP created_at, DROP comment, CHANGE record_id record_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE user_data_id user_data_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE users_data CHANGE nick nick VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
