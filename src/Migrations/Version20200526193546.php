<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200526193546 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE familia_produto (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(255) NOT NULL, nome VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grupo_usuarios (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permissoes (id INT AUTO_INCREMENT NOT NULL, grupo_id INT NOT NULL, rota VARCHAR(255) NOT NULL, liberado TINYINT(1) NOT NULL, INDEX IDX_7D2D6A4B9C833003 (grupo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pessoa (id INT AUTO_INCREMENT NOT NULL, tipo VARCHAR(255) NOT NULL, nome VARCHAR(255) NOT NULL, nome_fantasia VARCHAR(255) NOT NULL, cpf_cnpj VARCHAR(255) NOT NULL, rg VARCHAR(255) DEFAULT NULL, cnae INT DEFAULT NULL, observacoes LONGTEXT DEFAULT NULL, data_nascimento DATE DEFAULT NULL, ativo TINYINT(1) NOT NULL, cliente TINYINT(1) NOT NULL, fornecedor TINYINT(1) NOT NULL, funcionario TINYINT(1) NOT NULL, empresa TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pessoa_contato (id INT AUTO_INCREMENT NOT NULL, pessoa_id INT NOT NULL, nome VARCHAR(255) NOT NULL, telefone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_A5069B75DF6FA0A5 (pessoa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pessoa_endereco (id INT AUTO_INCREMENT NOT NULL, pessoa_id INT NOT NULL, uf VARCHAR(50) NOT NULL, cidade VARCHAR(255) NOT NULL, logradouro VARCHAR(255) NOT NULL, bairro VARCHAR(255) NOT NULL, complemento VARCHAR(255) DEFAULT NULL, numero INT NOT NULL, cep INT NOT NULL, ibge_estado INT NOT NULL, ibge_cidade INT NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_403BF131DF6FA0A5 (pessoa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produto (id INT AUTO_INCREMENT NOT NULL, familia_id INT NOT NULL, nome VARCHAR(255) NOT NULL, preco INT NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_5CAC49D7D02563A3 (familia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, grupo_id INT NOT NULL, pessoa_id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, api_token VARCHAR(255) DEFAULT NULL, login_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D6499C833003 (grupo_id), UNIQUE INDEX UNIQ_8D93D649DF6FA0A5 (pessoa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE permissoes ADD CONSTRAINT FK_7D2D6A4B9C833003 FOREIGN KEY (grupo_id) REFERENCES grupo_usuarios (id)');
        $this->addSql('ALTER TABLE pessoa_contato ADD CONSTRAINT FK_A5069B75DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES pessoa (id)');
        $this->addSql('ALTER TABLE pessoa_endereco ADD CONSTRAINT FK_403BF131DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES pessoa (id)');
        $this->addSql('ALTER TABLE produto ADD CONSTRAINT FK_5CAC49D7D02563A3 FOREIGN KEY (familia_id) REFERENCES familia_produto (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499C833003 FOREIGN KEY (grupo_id) REFERENCES grupo_usuarios (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES pessoa (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6497BA2F5EB ON user (api_token)');
        $this->addSql('CREATE TABLE questions (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, tipo INT NOT NULL, disciplina_id INT DEFAULT NULL, answer VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questions_opt (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, text VARCHAR(255) NOT NULL, isanswer TINYINT(1) NOT NULL, INDEX IDX_D329AFCC1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, public TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_questions (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, question_id INT NOT NULL, ordem INT NOT NULL, weight INT NOT NULL, INDEX IDX_8CBC2533853CD175 (quiz_id), INDEX IDX_8CBC25331E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE questions_opt ADD CONSTRAINT FK_D329AFCC1E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id)');
        $this->addSql('ALTER TABLE quiz_questions ADD CONSTRAINT FK_8CBC2533853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz_questions ADD CONSTRAINT FK_8CBC25331E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id)');
        $this->addSql('CREATE TABLE disciplina (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, segmento VARCHAR(255) NOT NULL, obrigatorio TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D52A30B056 FOREIGN KEY (disciplina_id) REFERENCES disciplina (id)');
        $this->addSql('CREATE INDEX IDX_8ADC54D52A30B056 ON questions (disciplina_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        // deletar as index primeiro
        $this->addSql('DROP INDEX UNIQ_8D93D6497BA2F5EB ON user');
        $this->addSql('ALTER TABLE produto DROP FOREIGN KEY FK_5CAC49D7D02563A3');
        $this->addSql('ALTER TABLE permissoes DROP FOREIGN KEY FK_7D2D6A4B9C833003');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499C833003');
        $this->addSql('ALTER TABLE pessoa_contato DROP FOREIGN KEY FK_A5069B75DF6FA0A5');
        $this->addSql('ALTER TABLE pessoa_endereco DROP FOREIGN KEY FK_403BF131DF6FA0A5');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649DF6FA0A5');
        $this->addSql('ALTER TABLE questions_opt DROP FOREIGN KEY FK_D329AFCC1E27F6BF');
        $this->addSql('ALTER TABLE quiz_questions DROP FOREIGN KEY FK_8CBC25331E27F6BF');
        $this->addSql('ALTER TABLE quiz_questions DROP FOREIGN KEY FK_8CBC2533853CD175');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D52A30B056');
        $this->addSql('DROP INDEX IDX_8ADC54D52A30B056 ON questions');

        // depois as tabelas
        $this->addSql('DROP TABLE familia_produto');
        $this->addSql('DROP TABLE grupo_usuarios');
        $this->addSql('DROP TABLE permissoes');
        $this->addSql('DROP TABLE pessoa');
        $this->addSql('DROP TABLE pessoa_contato');
        $this->addSql('DROP TABLE pessoa_endereco');
        $this->addSql('DROP TABLE produto');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE questions_opt');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_questions');
        $this->addSql('DROP TABLE disciplina');
    }
}
