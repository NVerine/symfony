<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210115204330 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE filial (id INT AUTO_INCREMENT NOT NULL, pessoa_id INT DEFAULT NULL, nome VARCHAR(255) NOT NULL, regime_tributario INT NOT NULL, timezone VARCHAR(255) DEFAULT NULL, pula_nf INT DEFAULT NULL, UNIQUE INDEX UNIQ_F5599759DF6FA0A5 (pessoa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE filial ADD CONSTRAINT FK_F5599759DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES pessoa (id)');
        $this->addSql('ALTER TABLE pessoa ADD endereco_principal_id INT DEFAULT NULL, ADD contato_principal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pessoa ADD CONSTRAINT FK_1CDFAB8270DAE131 FOREIGN KEY (endereco_principal_id) REFERENCES pessoa_endereco (id)');
        $this->addSql('ALTER TABLE pessoa ADD CONSTRAINT FK_1CDFAB829D16D50E FOREIGN KEY (contato_principal_id) REFERENCES pessoa_contato (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1CDFAB8270DAE131 ON pessoa (endereco_principal_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1CDFAB829D16D50E ON pessoa (contato_principal_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE filial');
        $this->addSql('ALTER TABLE pessoa DROP FOREIGN KEY FK_1CDFAB8270DAE131');
        $this->addSql('ALTER TABLE pessoa DROP FOREIGN KEY FK_1CDFAB829D16D50E');
        $this->addSql('DROP INDEX UNIQ_1CDFAB8270DAE131 ON pessoa');
        $this->addSql('DROP INDEX UNIQ_1CDFAB829D16D50E ON pessoa');
        $this->addSql('ALTER TABLE pessoa DROP endereco_principal_id, DROP contato_principal_id');
    }
}
