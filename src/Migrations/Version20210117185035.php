<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210117185035 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comercial (id INT AUTO_INCREMENT NOT NULL, cliente_id INT NOT NULL, tipo VARCHAR(255) NOT NULL, modelo INT NOT NULL, finalidade INT NOT NULL, data_emissao DATETIME NOT NULL, num_nf INT DEFAULT NULL, natureza VARCHAR(255) DEFAULT NULL, info_fisco VARCHAR(255) DEFAULT NULL, nf_referencia VARCHAR(255) DEFAULT NULL, info_complementar VARCHAR(255) DEFAULT NULL, INDEX IDX_ED11C46CDE734E51 (cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comercial_itens (id INT AUTO_INCREMENT NOT NULL, comercial_id INT NOT NULL, produto_id INT NOT NULL, operacao_id INT DEFAULT NULL, quantidade INT NOT NULL, valor_unitario DOUBLE PRECISION NOT NULL, valor_bruto DOUBLE PRECISION NOT NULL, perc_desconto DOUBLE PRECISION NOT NULL, valor_desconto DOUBLE PRECISION NOT NULL, perc_ipi DOUBLE PRECISION NOT NULL, valor_ipi DOUBLE PRECISION NOT NULL, valor_total DOUBLE PRECISION NOT NULL, INDEX IDX_6A37A421E2AAC521 (comercial_id), INDEX IDX_6A37A421105CFD56 (produto_id), INDEX IDX_6A37A4215BD59BA3 (operacao_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comercial ADD CONSTRAINT FK_ED11C46CDE734E51 FOREIGN KEY (cliente_id) REFERENCES pessoa (id)');
        $this->addSql('ALTER TABLE comercial_itens ADD CONSTRAINT FK_6A37A421E2AAC521 FOREIGN KEY (comercial_id) REFERENCES comercial (id)');
        $this->addSql('ALTER TABLE comercial_itens ADD CONSTRAINT FK_6A37A421105CFD56 FOREIGN KEY (produto_id) REFERENCES produto (id)');
        $this->addSql('ALTER TABLE comercial_itens ADD CONSTRAINT FK_6A37A4215BD59BA3 FOREIGN KEY (operacao_id) REFERENCES trib_tipo_operacao (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comercial_itens DROP FOREIGN KEY FK_6A37A421E2AAC521');
        $this->addSql('DROP TABLE comercial');
        $this->addSql('DROP TABLE comercial_itens');
    }
}
