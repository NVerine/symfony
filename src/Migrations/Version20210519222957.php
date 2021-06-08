<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210519222957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_filial (user_id INT NOT NULL, filial_id INT NOT NULL, INDEX IDX_900B8C64A76ED395 (user_id), INDEX IDX_900B8C64299B2577 (filial_id), PRIMARY KEY(user_id, filial_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_filial ADD CONSTRAINT FK_900B8C64A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_filial ADD CONSTRAINT FK_900B8C64299B2577 FOREIGN KEY (filial_id) REFERENCES filial (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE filial DROP FOREIGN KEY FK_F5599759DF6FA0A5');
        $this->addSql('DROP INDEX UNIQ_F5599759DF6FA0A5 ON filial');
        $this->addSql('ALTER TABLE filial CHANGE pessoa_id socio_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE filial ADD CONSTRAINT FK_F5599759DA04E6A9 FOREIGN KEY (socio_id) REFERENCES pessoa (id)');
        $this->addSql('CREATE INDEX IDX_F5599759DA04E6A9 ON filial (socio_id)');
        $this->addSql('ALTER TABLE pessoa ADD filial_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pessoa ADD CONSTRAINT FK_1CDFAB82299B2577 FOREIGN KEY (filial_id) REFERENCES filial (id)');
        $this->addSql('CREATE INDEX IDX_1CDFAB82299B2577 ON pessoa (filial_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_filial');
        $this->addSql('ALTER TABLE filial DROP FOREIGN KEY FK_F5599759DA04E6A9');
        $this->addSql('DROP INDEX IDX_F5599759DA04E6A9 ON filial');
        $this->addSql('ALTER TABLE filial CHANGE socio_id pessoa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE filial ADD CONSTRAINT FK_F5599759DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES pessoa (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5599759DF6FA0A5 ON filial (pessoa_id)');
        $this->addSql('ALTER TABLE pessoa DROP FOREIGN KEY FK_1CDFAB82299B2577');
        $this->addSql('DROP INDEX IDX_1CDFAB82299B2577 ON pessoa');
        $this->addSql('ALTER TABLE pessoa DROP filial_id');
    }
}
