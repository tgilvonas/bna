<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230919180002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, article_title VARCHAR(255) NOT NULL, article_url VARCHAR(255) NOT NULL, img_original VARCHAR(255) NOT NULL, img VARCHAR(255) DEFAULT NULL, intro LONGTEXT DEFAULT NULL, content LONGTEXT NOT NULL, published SMALLINT NOT NULL, on_first_page SMALLINT NOT NULL, has_full_content SMALLINT NOT NULL, article_description LONGTEXT DEFAULT NULL, article_keywords LONGTEXT NOT NULL, date DATE NOT NULL, categories_id BIGINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE banners (id INT AUTO_INCREMENT NOT NULL, img LONGTEXT NOT NULL, url VARCHAR(255) NOT NULL, innew SMALLINT NOT NULL, published SMALLINT NOT NULL, ord INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, active SMALLINT NOT NULL, category_name VARCHAR(255) NOT NULL, category_url VARCHAR(255) NOT NULL, category_description LONGTEXT DEFAULT NULL, category_keywords LONGTEXT DEFAULT NULL, ord SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, commenter VARCHAR(255) NOT NULL, articles_id BIGINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, tag VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE banners');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE tags');
    }
}
