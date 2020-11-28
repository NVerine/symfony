<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200831190008 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE trib_cfop (id INT AUTO_INCREMENT NOT NULL, codigo INT NOT NULL, descricao VARCHAR(350) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trib_cst (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) DEFAULT NULL, codigo INT NOT NULL, tipo VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trib_ncm (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(300) NOT NULL, codigo VARCHAR(100) NOT NULL, aliquota DOUBLE PRECISION DEFAULT NULL, descricao VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trib_tipo_operacao (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, codigo INT NOT NULL, descricao VARCHAR(255) DEFAULT NULL, tipo VARCHAR(10) NOT NULL, csosn INT DEFAULT NULL, icmstipo INT NOT NULL, icmsbase DOUBLE PRECISION NOT NULL, pisaliquota DOUBLE PRECISION NOT NULL, cofinsaliquota DOUBLE PRECISION NOT NULL, issqnaliquota DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('ALTER TABLE trib_cst ADD trib_tipo_operacao_origem_id INT DEFAULT NULL');
//        $this->addSql('ALTER TABLE trib_cst ADD CONSTRAINT FK_70430E236AD9761A FOREIGN KEY (trib_tipo_operacao_origem_id) REFERENCES trib_tipo_operacao (id)');
//        $this->addSql('CREATE INDEX IDX_70430E236AD9761A ON trib_cst (trib_tipo_operacao_origem_id)');
        $this->addSql('ALTER TABLE trib_ncm ADD produto_id INT DEFAULT NULL, ADD trib_tipo_operacao_trib_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trib_ncm ADD CONSTRAINT FK_563227E1105CFD56 FOREIGN KEY (produto_id) REFERENCES produto (id)');
        $this->addSql('ALTER TABLE trib_ncm ADD CONSTRAINT FK_563227E19B559DF5 FOREIGN KEY (trib_tipo_operacao_trib_id) REFERENCES trib_tipo_operacao (id)');
        $this->addSql('CREATE INDEX IDX_563227E1105CFD56 ON trib_ncm (produto_id)');
        $this->addSql('CREATE INDEX IDX_563227E19B559DF5 ON trib_ncm (trib_tipo_operacao_trib_id)');
        foreach (explode(';', file_get_contents(__DIR__ . '/raw/CFOP.sql')) as $sql) {
            if(!empty($sql)){
                $this->addSql($sql);
            }
        }
        foreach (explode(';', file_get_contents(__DIR__ . '/raw/NCM.sql')) as $sql) {
            if(!empty($sql)){
                $this->addSql($sql);
            }
        }
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE trib_cfop');
        $this->addSql('DROP TABLE trib_cst');
        $this->addSql('DROP TABLE trib_ncm');
//        $this->addSql('ALTER TABLE trib_cst DROP FOREIGN KEY FK_70430E236AD9761A');
        $this->addSql('ALTER TABLE trib_ncm DROP FOREIGN KEY FK_563227E19B559DF5');
        $this->addSql('DROP TABLE trib_tipo_operacao');
//        $this->addSql('DROP INDEX IDX_70430E236AD9761A ON trib_cst');
//        $this->addSql('ALTER TABLE trib_cst DROP trib_tipo_operacao_origem_id');
        $this->addSql('ALTER TABLE trib_ncm DROP FOREIGN KEY FK_563227E1105CFD56');
        $this->addSql('DROP INDEX IDX_563227E1105CFD56 ON trib_ncm');
        $this->addSql('DROP INDEX IDX_563227E19B559DF5 ON trib_ncm');
        $this->addSql('ALTER TABLE trib_ncm DROP produto_id, DROP trib_tipo_operacao_trib_id');
    }
}
