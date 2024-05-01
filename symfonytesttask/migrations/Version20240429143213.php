<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429143213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_user ADD group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tbl_user ADD CONSTRAINT FK_38B383A1D2112630 FOREIGN KEY (group_id) REFERENCES tbl_group (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_38B383A1E7927C74 ON tbl_user (email)');
        $this->addSql('CREATE INDEX IDX_38B383A1D2112630 ON tbl_user (group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tbl_user DROP FOREIGN KEY FK_38B383A1D2112630');
        $this->addSql('DROP INDEX UNIQ_38B383A1E7927C74 ON tbl_user');
        $this->addSql('DROP INDEX IDX_38B383A1D2112630 ON tbl_user');
        $this->addSql('ALTER TABLE tbl_user DROP group_id');
    }
}
