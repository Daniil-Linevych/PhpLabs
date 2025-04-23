<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250421183941 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE users (id SERIAL NOT NULL, full_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE staff ADD user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE staff ADD CONSTRAINT FK_426EF392A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_426EF392A76ED395 ON staff (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE visitors ADD user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE visitors ADD CONSTRAINT FK_7B74A43FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_7B74A43FA76ED395 ON visitors (user_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE staff DROP CONSTRAINT FK_426EF392A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE visitors DROP CONSTRAINT FK_7B74A43FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE users
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_7B74A43FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE visitors DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_426EF392A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE staff DROP user_id
        SQL);
    }
}
