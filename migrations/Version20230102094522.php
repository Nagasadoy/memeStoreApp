<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230102094522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meme (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, meme_file_id INT NOT NULL, user_meme_name VARCHAR(255) NOT NULL, INDEX IDX_4B9F7934A76ED395 (user_id), INDEX IDX_4B9F79345CE79DA0 (meme_file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meme_file (id INT AUTO_INCREMENT NOT NULL, common_name VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_meme (tag_id INT NOT NULL, meme_id INT NOT NULL, INDEX IDX_A590C682BAD26311 (tag_id), INDEX IDX_A590C682DB6EC45D (meme_id), PRIMARY KEY(tag_id, meme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meme ADD CONSTRAINT FK_4B9F7934A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE meme ADD CONSTRAINT FK_4B9F79345CE79DA0 FOREIGN KEY (meme_file_id) REFERENCES meme_file (id)');
        $this->addSql('ALTER TABLE tag_meme ADD CONSTRAINT FK_A590C682BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_meme ADD CONSTRAINT FK_A590C682DB6EC45D FOREIGN KEY (meme_id) REFERENCES meme (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meme DROP FOREIGN KEY FK_4B9F7934A76ED395');
        $this->addSql('ALTER TABLE meme DROP FOREIGN KEY FK_4B9F79345CE79DA0');
        $this->addSql('ALTER TABLE tag_meme DROP FOREIGN KEY FK_A590C682BAD26311');
        $this->addSql('ALTER TABLE tag_meme DROP FOREIGN KEY FK_A590C682DB6EC45D');
        $this->addSql('DROP TABLE meme');
        $this->addSql('DROP TABLE meme_file');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_meme');
        $this->addSql('DROP TABLE `user`');
    }
}
