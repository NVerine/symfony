<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201017022832 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questions DROP weight');
        $this->addSql('ALTER TABLE questions_opt ADD isanswer TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE quiz_questions ADD weight INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questions ADD weight INT DEFAULT NULL');
        $this->addSql('ALTER TABLE questions_opt DROP isanswer');
        $this->addSql('ALTER TABLE quiz_questions DROP weight');
    }
}
