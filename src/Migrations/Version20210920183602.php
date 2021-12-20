<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210920183602 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE branch (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, timezone VARCHAR(255) DEFAULT NULL, INDEX IDX_BB861B1F7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permissions (id INT AUTO_INCREMENT NOT NULL, group_id INT NOT NULL, route VARCHAR(255) NOT NULL, is_open TINYINT(1) NOT NULL, INDEX IDX_2DEDCC6FFE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, branch_id INT DEFAULT NULL, main_contact_id INT DEFAULT NULL, main_address_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, observations LONGTEXT DEFAULT NULL, birth_date DATE DEFAULT NULL, is_active TINYINT(1) NOT NULL, is_customer TINYINT(1) NOT NULL, is_supplier TINYINT(1) NOT NULL, is_employee TINYINT(1) NOT NULL, INDEX IDX_34DCD176DCD6CC49 (branch_id), UNIQUE INDEX UNIQ_34DCD176DF595129 (main_contact_id), UNIQUE INDEX UNIQ_34DCD176CD4FDB16 (main_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_address (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, uf VARCHAR(50) NOT NULL, city VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, district VARCHAR(255) NOT NULL, address_complement VARCHAR(255) DEFAULT NULL, number LONGTEXT NOT NULL, zip INT NOT NULL, ibge_cidade INT NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_2FD0DC08217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_contact (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, contact_name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_6EFC55B1217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, group_id INT DEFAULT NULL, person_id INT NOT NULL, active_branch_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D649FE54D947 (group_id), INDEX IDX_8D93D649217BBB47 (person_id), INDEX IDX_8D93D649CA408F7D (active_branch_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_branch (user_id INT NOT NULL, branch_id INT NOT NULL, INDEX IDX_DED40022A76ED395 (user_id), INDEX IDX_DED40022DCD6CC49 (branch_id), PRIMARY KEY(user_id, branch_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_tokens (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, origin VARCHAR(255) NOT NULL, user_agent VARCHAR(255) NOT NULL, date DATETIME NOT NULL, token VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_CF080AB3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reports (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, column_name VARCHAR(255) NOT NULL, column_name_replacer VARCHAR(255) DEFAULT NULL, level INT NOT NULL, column_order INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql("insert into users_group (id, name) values (1, 'superAdmin')");
        $this->addSql("insert into branch (id, owner_id, name, timezone) VALUES (1, 1, 'Default company', 'br')");
        $this->addSql("insert into person (id, name, type, nickname, is_active, is_employee, is_customer, is_supplier, branch_id) VALUES
                                (1, 'Super Administrator', 'F', 'Super Admin', true, false, false, false, 1),
                                (2, 'Default company', 'J', 'Default company', true, false, false, false, 1)");
        $this->addSql("insert into user (id, group_id, person_id, active_branch_id, username, roles, password) values(1, 1, 1, 1, 'Admin', '[]', '\$argon2id\$v=19\$m=65536,t=4,p=1\$oyJbzkeY4DEjw0W7YGqt5g\$s8ndLoIc8WC2G2G/XzYwnefuzFBwDslgVQJIOSDEHRo')");
        $this->addSql('ALTER TABLE branch ADD CONSTRAINT FK_BB861B1F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FFE54D947 FOREIGN KEY (group_id) REFERENCES users_group (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176DF595129 FOREIGN KEY (main_contact_id) REFERENCES person_contact (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176CD4FDB16 FOREIGN KEY (main_address_id) REFERENCES person_address (id)');
        $this->addSql('ALTER TABLE person_address ADD CONSTRAINT FK_2FD0DC08217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_contact ADD CONSTRAINT FK_6EFC55B1217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649FE54D947 FOREIGN KEY (group_id) REFERENCES users_group (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CA408F7D FOREIGN KEY (active_branch_id) REFERENCES branch (id)');
        $this->addSql('ALTER TABLE user_branch ADD CONSTRAINT FK_DED40022A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_branch ADD CONSTRAINT FK_DED40022DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_tokens ADD CONSTRAINT FK_CF080AB3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql(file_get_contents(__DIR__ . '/View/VW_Permissions.sql'));
        $this->addSql(file_get_contents(__DIR__ . '/View/VW_Person.sql'));
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176DCD6CC49');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CA408F7D');
        $this->addSql('ALTER TABLE user_branch DROP FOREIGN KEY FK_DED40022DCD6CC49');
        $this->addSql('ALTER TABLE branch DROP FOREIGN KEY FK_BB861B1F7E3C61F9');
        $this->addSql('ALTER TABLE person_address DROP FOREIGN KEY FK_2FD0DC08217BBB47');
        $this->addSql('ALTER TABLE person_contact DROP FOREIGN KEY FK_6EFC55B1217BBB47');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649217BBB47');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176CD4FDB16');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176DF595129');
        $this->addSql('ALTER TABLE user_branch DROP FOREIGN KEY FK_DED40022A76ED395');
        $this->addSql('ALTER TABLE user_tokens DROP FOREIGN KEY FK_CF080AB3A76ED395');
        $this->addSql('ALTER TABLE permissions DROP FOREIGN KEY FK_2DEDCC6FFE54D947');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649FE54D947');
        $this->addSql('DROP TABLE branch');
        $this->addSql('DROP TABLE permissions');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_address');
        $this->addSql('DROP TABLE person_contact');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_branch');
        $this->addSql('DROP TABLE user_tokens');
        $this->addSql('DROP TABLE users_group');
        $this->addSql('DROP TABLE reports');
    }
}
