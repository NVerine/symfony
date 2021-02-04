<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210118180442 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comercial CHANGE data_lancamento data_lancamento DATETIME NOT NULL');
        $this->addSql('ALTER TABLE trib_ncm DROP FOREIGN KEY FK_563227E19B559DF5');
        $this->addSql('DROP INDEX IDX_563227E19B559DF5 ON trib_ncm');
        $this->addSql('ALTER TABLE trib_ncm DROP trib_tipo_operacao_trib_id');
        $this->addSql('ALTER TABLE trib_tipo_operacao ADD cst_origem_id INT DEFAULT NULL, ADD cst_trib_id INT DEFAULT NULL, ADD cfop_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trib_tipo_operacao ADD CONSTRAINT FK_80B5959F8E937A2F FOREIGN KEY (cst_origem_id) REFERENCES trib_cst (id)');
        $this->addSql('ALTER TABLE trib_tipo_operacao ADD CONSTRAINT FK_80B5959F5C466299 FOREIGN KEY (cst_trib_id) REFERENCES trib_cst (id)');
        $this->addSql('ALTER TABLE trib_tipo_operacao ADD CONSTRAINT FK_80B5959F10C23963 FOREIGN KEY (cfop_id) REFERENCES trib_cfop (id)');
        $this->addSql('CREATE INDEX IDX_80B5959F8E937A2F ON trib_tipo_operacao (cst_origem_id)');
        $this->addSql('CREATE INDEX IDX_80B5959F5C466299 ON trib_tipo_operacao (cst_trib_id)');
        $this->addSql('CREATE INDEX IDX_80B5959F10C23963 ON trib_tipo_operacao (cfop_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comercial CHANGE data_lancamento data_lancamento DATETIME DEFAULT \'2021-01-17 17:55:55\' NOT NULL');
        $this->addSql('ALTER TABLE trib_ncm ADD trib_tipo_operacao_trib_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trib_ncm ADD CONSTRAINT FK_563227E19B559DF5 FOREIGN KEY (trib_tipo_operacao_trib_id) REFERENCES trib_tipo_operacao (id)');
        $this->addSql('CREATE INDEX IDX_563227E19B559DF5 ON trib_ncm (trib_tipo_operacao_trib_id)');
        $this->addSql('ALTER TABLE trib_tipo_operacao DROP FOREIGN KEY FK_80B5959F8E937A2F');
        $this->addSql('ALTER TABLE trib_tipo_operacao DROP FOREIGN KEY FK_80B5959F5C466299');
        $this->addSql('ALTER TABLE trib_tipo_operacao DROP FOREIGN KEY FK_80B5959F10C23963');
        $this->addSql('DROP INDEX IDX_80B5959F8E937A2F ON trib_tipo_operacao');
        $this->addSql('DROP INDEX IDX_80B5959F5C466299 ON trib_tipo_operacao');
        $this->addSql('DROP INDEX IDX_80B5959F10C23963 ON trib_tipo_operacao');
        $this->addSql('ALTER TABLE trib_tipo_operacao DROP cst_origem_id, DROP cst_trib_id, DROP cfop_id');
    }
}
