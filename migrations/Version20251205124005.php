<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251205124005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__bot_blueprint AS SELECT id, type, args, frequency FROM bot_blueprint');
        $this->addSql('DROP TABLE bot_blueprint');
        $this->addSql('CREATE TABLE bot_blueprint (id BLOB NOT NULL --(DC2Type:uuid)
        , type VARCHAR(255) NOT NULL, args CLOB NOT NULL --(DC2Type:object)
        , frequency VARCHAR(255) NOT NULL --(DC2Type:dateinterval)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO bot_blueprint (id, type, args, frequency) SELECT id, type, args, frequency FROM __temp__bot_blueprint');
        $this->addSql('DROP TABLE __temp__bot_blueprint');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__bot_blueprint AS SELECT id, type, args, frequency FROM bot_blueprint');
        $this->addSql('DROP TABLE bot_blueprint');
        $this->addSql('CREATE TABLE bot_blueprint (id BLOB NOT NULL --(DC2Type:uuid)
        , type VARCHAR(255) NOT NULL, args CLOB NOT NULL --(DC2Type:json)
        , frequency VARCHAR(255) NOT NULL --(DC2Type:dateinterval)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO bot_blueprint (id, type, args, frequency) SELECT id, type, args, frequency FROM __temp__bot_blueprint');
        $this->addSql('DROP TABLE __temp__bot_blueprint');
    }
}
