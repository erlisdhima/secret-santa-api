<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250623114415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, organizer_name VARCHAR(100) NOT NULL, invite_code VARCHAR(100) NOT NULL, max_players INT NOT NULL, gift_budget NUMERIC(10, 2) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5387574A6F21F112 (invite_code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE gift_assignments (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, gift_id INT NOT NULL, receiver_id INT NOT NULL, INDEX IDX_7FF9AEA71F7E88B (event_id), INDEX IDX_7FF9AEA97A95A83 (gift_id), UNIQUE INDEX UNIQ_7FF9AEACD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE gifts (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, giver_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, title VARCHAR(150) NOT NULL, category VARCHAR(100) NOT NULL, price NUMERIC(10, 2) NOT NULL, product_url VARCHAR(255) DEFAULT NULL, INDEX IDX_651BCF2F71F7E88B (event_id), UNIQUE INDEX UNIQ_651BCF2F75BD1D29 (giver_id), INDEX IDX_651BCF2FCD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE players (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_264E43A671F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE preferences (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, value VARCHAR(100) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_E931A6F599E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gift_assignments ADD CONSTRAINT FK_7FF9AEA71F7E88B FOREIGN KEY (event_id) REFERENCES events (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gift_assignments ADD CONSTRAINT FK_7FF9AEA97A95A83 FOREIGN KEY (gift_id) REFERENCES gifts (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gift_assignments ADD CONSTRAINT FK_7FF9AEACD53EDB6 FOREIGN KEY (receiver_id) REFERENCES players (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gifts ADD CONSTRAINT FK_651BCF2F71F7E88B FOREIGN KEY (event_id) REFERENCES events (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gifts ADD CONSTRAINT FK_651BCF2F75BD1D29 FOREIGN KEY (giver_id) REFERENCES players (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gifts ADD CONSTRAINT FK_651BCF2FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES players (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE players ADD CONSTRAINT FK_264E43A671F7E88B FOREIGN KEY (event_id) REFERENCES events (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE preferences ADD CONSTRAINT FK_E931A6F599E6F5DF FOREIGN KEY (player_id) REFERENCES players (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE gift_assignments DROP FOREIGN KEY FK_7FF9AEA71F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gift_assignments DROP FOREIGN KEY FK_7FF9AEA97A95A83
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gift_assignments DROP FOREIGN KEY FK_7FF9AEACD53EDB6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gifts DROP FOREIGN KEY FK_651BCF2F71F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gifts DROP FOREIGN KEY FK_651BCF2F75BD1D29
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE gifts DROP FOREIGN KEY FK_651BCF2FCD53EDB6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE players DROP FOREIGN KEY FK_264E43A671F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE preferences DROP FOREIGN KEY FK_E931A6F599E6F5DF
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE events
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE gift_assignments
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE gifts
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE players
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE preferences
        SQL);
    }
}
