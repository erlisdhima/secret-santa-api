<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250623115622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE gifts DROP FOREIGN KEY FK_651BCF2FCD53EDB6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_651BCF2FCD53EDB6 ON gifts
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gifts DROP receiver_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE gifts ADD receiver_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gifts ADD CONSTRAINT FK_651BCF2FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES players (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_651BCF2FCD53EDB6 ON gifts (receiver_id)
        SQL);
    }
}
