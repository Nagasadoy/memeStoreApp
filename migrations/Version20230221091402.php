<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221091402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meme (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, user_meme_name VARCHAR(255) NOT NULL, common_name VARCHAR(255) NOT NULL, INDEX IDX_4B9F7934A76ED395 (user_id), UNIQUE INDEX user_meme_unique (user_id, user_meme_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_meme (tag_id INT NOT NULL, meme_id INT NOT NULL, INDEX IDX_A590C682BAD26311 (tag_id), INDEX IDX_A590C682DB6EC45D (meme_id), PRIMARY KEY(tag_id, meme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meme ADD CONSTRAINT FK_4B9F7934A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE tag_meme ADD CONSTRAINT FK_A590C682BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_meme ADD CONSTRAINT FK_A590C682DB6EC45D FOREIGN KEY (meme_id) REFERENCES meme (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meme DROP FOREIGN KEY FK_4B9F7934A76ED395');
        $this->addSql('ALTER TABLE tag_meme DROP FOREIGN KEY FK_A590C682BAD26311');
        $this->addSql('ALTER TABLE tag_meme DROP FOREIGN KEY FK_A590C682DB6EC45D');
        $this->addSql('DROP TABLE meme');
        $this->addSql('DROP TABLE tag_meme');
    }
}
