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
final class Version20200624102516 extends AbstractMigration
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

        $this->addSql('ALTER TABLE borrowings DROP FOREIGN KEY FK_7547A7B46BF700BD');
        $this->addSql('DROP TABLE borrowings_status');
        $this->addSql('ALTER TABLE users CHANGE user_data_id user_data_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE users_data CHANGE nick nick VARCHAR(45) DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_7547A7B46BF700BD ON borrowings');
        $this->addSql('ALTER TABLE borrowings DROP status_id, CHANGE record_id record_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE comment comment VARCHAR(255) DEFAULT NULL');
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

        $this->addSql('CREATE TABLE borrowings_status (id INT AUTO_INCREMENT NOT NULL, is_executed TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE borrowings ADD status_id INT DEFAULT NULL, CHANGE record_id record_id INT DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT \'NULL\', CHANGE comment comment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE borrowings ADD CONSTRAINT FK_7547A7B46BF700BD FOREIGN KEY (status_id) REFERENCES borrowings_status (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7547A7B46BF700BD ON borrowings (status_id)');
        $this->addSql('ALTER TABLE users CHANGE user_data_id user_data_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE users_data CHANGE nick nick VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
