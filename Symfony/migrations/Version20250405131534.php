<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250405131534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE exhibition_staff (exhibition_id INT NOT NULL, staff_id INT NOT NULL, PRIMARY KEY(exhibition_id, staff_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1BD03F732A7D4494 ON exhibition_staff (exhibition_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_1BD03F73D4D57CD ON exhibition_staff (staff_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exhibition_staff ADD CONSTRAINT FK_1BD03F732A7D4494 FOREIGN KEY (exhibition_id) REFERENCES exhibitions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exhibition_staff ADD CONSTRAINT FK_1BD03F73D4D57CD FOREIGN KEY (staff_id) REFERENCES staff (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exhibits ADD exhibition_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exhibits ALTER author SET NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exhibits ALTER creation_year TYPE INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exhibits ADD CONSTRAINT FK_65D24A22A7D4494 FOREIGN KEY (exhibition_id) REFERENCES exhibitions (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_65D24A22A7D4494 ON exhibits (exhibition_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE staff ALTER "position" TYPE VARCHAR(100)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE staff ALTER salary TYPE DOUBLE PRECISION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets ADD visitor_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets ADD exhibition_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets ALTER price TYPE DOUBLE PRECISION
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets ADD CONSTRAINT FK_54469DF470BEE6D FOREIGN KEY (visitor_id) REFERENCES visitors (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets ADD CONSTRAINT FK_54469DF42A7D4494 FOREIGN KEY (exhibition_id) REFERENCES exhibitions (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_54469DF470BEE6D ON tickets (visitor_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_54469DF42A7D4494 ON tickets (exhibition_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exhibition_staff DROP CONSTRAINT FK_1BD03F732A7D4494
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exhibition_staff DROP CONSTRAINT FK_1BD03F73D4D57CD
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE exhibition_staff
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE staff ALTER position TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE staff ALTER salary TYPE NUMERIC(10, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets DROP CONSTRAINT FK_54469DF470BEE6D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets DROP CONSTRAINT FK_54469DF42A7D4494
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_54469DF470BEE6D
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_54469DF42A7D4494
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets DROP visitor_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets DROP exhibition_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tickets ALTER price TYPE NUMERIC(10, 2)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exhibits DROP CONSTRAINT FK_65D24A22A7D4494
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_65D24A22A7D4494
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exhibits DROP exhibition_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exhibits ALTER creation_year TYPE VARCHAR(255)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE exhibits ALTER author DROP NOT NULL
        SQL);
    }
}
