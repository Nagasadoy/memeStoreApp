<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221230114044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE combination (id INT AUTO_INCREMENT NOT NULL, memes_id INT DEFAULT NULL, tags_id INT DEFAULT NULL, users_id INT DEFAULT NULL, INDEX IDX_DE091AAFF270FEA8 (memes_id), INDEX IDX_DE091AAF8D7B4FB4 (tags_id), INDEX IDX_DE091AAF67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE combination ADD CONSTRAINT FK_DE091AAFF270FEA8 FOREIGN KEY (memes_id) REFERENCES meme (id)');
        $this->addSql('ALTER TABLE combination ADD CONSTRAINT FK_DE091AAF8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tag (id)');
        $this->addSql('ALTER TABLE combination ADD CONSTRAINT FK_DE091AAF67B3B43D FOREIGN KEY (users_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE combination DROP FOREIGN KEY FK_DE091AAFF270FEA8');
        $this->addSql('ALTER TABLE combination DROP FOREIGN KEY FK_DE091AAF8D7B4FB4');
        $this->addSql('ALTER TABLE combination DROP FOREIGN KEY FK_DE091AAF67B3B43D');
        $this->addSql('DROP TABLE combination');
        $this->addSql('DROP TABLE meme');
        $this->addSql('DROP TABLE tag');
    }
}
