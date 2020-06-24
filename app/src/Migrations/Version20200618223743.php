<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618223743 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9C7F5D5F6');
        $this->addSql('DROP TABLE usersdata');
        $this->addSql('ALTER TABLE borrowings CHANGE record_id record_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_1483A5E9C7F5D5F6 ON users');
        $this->addSql('ALTER TABLE users DROP users_data_id, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE usersdata (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(45) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE borrowings CHANGE record_id record_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD users_data_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9C7F5D5F6 FOREIGN KEY (users_data_id) REFERENCES usersdata (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9C7F5D5F6 ON users (users_data_id)');
    }
}
