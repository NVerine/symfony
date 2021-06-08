<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210604221455 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD filial_ativa_id INT');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494D167A6 FOREIGN KEY (filial_ativa_id) REFERENCES filial (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6494D167A6 ON user (filial_ativa_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494D167A6');
        $this->addSql('DROP INDEX IDX_8D93D6494D167A6 ON user');
        $this->addSql('ALTER TABLE user DROP filial_ativa_id');
    }
}
