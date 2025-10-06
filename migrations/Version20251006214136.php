<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251006214136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id BLOB NOT NULL --(DC2Type:uuid)
        , owner_id BLOB NOT NULL --(DC2Type:uuid)
        , product_name VARCHAR(255) NOT NULL, quantity INTEGER NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_1F1B251E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES trader (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1F1B251E7E3C61F9 ON item (owner_id)');
        $this->addSql('CREATE TABLE offer (id BLOB NOT NULL --(DC2Type:uuid)
        , seller_id BLOB NOT NULL --(DC2Type:uuid)
        , product_name VARCHAR(255) NOT NULL, quantity INTEGER NOT NULL, total_price INTEGER NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_29D6873E8DE820D9 FOREIGN KEY (seller_id) REFERENCES trader (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_29D6873E8DE820D9 ON offer (seller_id)');
        $this->addSql('CREATE TABLE trader (id BLOB NOT NULL --(DC2Type:uuid)
        , balance INTEGER NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE trader');
    }
}
