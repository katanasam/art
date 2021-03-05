<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210305185040 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('ALTER TABLE `like` ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AC6340B3F675F31B ON `like` (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('DROP INDEX IDX_9474526CF675F31B ON comment');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3F675F31B');
        $this->addSql('DROP INDEX IDX_AC6340B3F675F31B ON `like`');
        $this->addSql('ALTER TABLE `like` DROP author_id');
    }
}
