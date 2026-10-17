<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025131315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles CHANGE published published TINYINT(1) NOT NULL, CHANGE on_first_page on_first_page TINYINT(1) NOT NULL, CHANGE has_full_content has_full_content TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE banners CHANGE img img LONGTEXT NOT NULL, CHANGE innew innew TINYINT(1) NOT NULL, CHANGE published published TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE categories CHANGE active active TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories CHANGE active active SMALLINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE banners CHANGE img img LONGTEXT DEFAULT NULL, CHANGE innew innew SMALLINT NOT NULL, CHANGE published published SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168A21214B7');
        $this->addSql('ALTER TABLE articles CHANGE published published SMALLINT NOT NULL, CHANGE on_first_page on_first_page SMALLINT NOT NULL, CHANGE has_full_content has_full_content SMALLINT NOT NULL');
    }
}
