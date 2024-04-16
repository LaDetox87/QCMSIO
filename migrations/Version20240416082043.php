<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240416082043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz ADD COLUMN is_graded BOOLEAN DEFAULT false');
        $this->addSql('CREATE TEMPORARY TABLE __temp__result AS SELECT id, quiz_id, user_id, score FROM result');
        $this->addSql('DROP TABLE result');
        $this->addSql('CREATE TABLE result (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, quiz_id INTEGER DEFAULT NULL, user_id INTEGER DEFAULT NULL, score INTEGER DEFAULT NULL, CONSTRAINT FK_136AC113853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_136AC113A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO result (id, quiz_id, user_id, score) SELECT id, quiz_id, user_id, score FROM __temp__result');
        $this->addSql('DROP TABLE __temp__result');
        $this->addSql('CREATE INDEX IDX_136AC113A76ED395 ON result (user_id)');
        $this->addSql('CREATE INDEX IDX_136AC113853CD175 ON result (quiz_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__quiz AS SELECT id, theme_id, title, is_published FROM quiz');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('CREATE TABLE quiz (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, theme_id INTEGER DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, is_published BOOLEAN DEFAULT NULL, CONSTRAINT FK_A412FA9259027487 FOREIGN KEY (theme_id) REFERENCES theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO quiz (id, theme_id, title, is_published) SELECT id, theme_id, title, is_published FROM __temp__quiz');
        $this->addSql('DROP TABLE __temp__quiz');
        $this->addSql('CREATE INDEX IDX_A412FA9259027487 ON quiz (theme_id)');
        $this->addSql('ALTER TABLE result ADD COLUMN has_answered BOOLEAN DEFAULT NULL');
    }
}
