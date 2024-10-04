<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001223508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity DROP date');
        $this->addSql('ALTER TABLE signup DROP FOREIGN KEY FK_4EA31EEF81C06096');
        $this->addSql('DROP INDEX IDX_4EA31EEF81C06096 ON signup');
        $this->addSql('ALTER TABLE signup CHANGE activity_id activity_date_id INT NOT NULL');
        $this->addSql('ALTER TABLE signup ADD CONSTRAINT FK_4EA31EEFB8C5408B FOREIGN KEY (activity_date_id) REFERENCES activity_date (id)');
        $this->addSql('CREATE INDEX IDX_4EA31EEFB8C5408B ON signup (activity_date_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity ADD date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE signup DROP FOREIGN KEY FK_4EA31EEFB8C5408B');
        $this->addSql('DROP INDEX IDX_4EA31EEFB8C5408B ON signup');
        $this->addSql('ALTER TABLE signup CHANGE activity_date_id activity_id INT NOT NULL');
        $this->addSql('ALTER TABLE signup ADD CONSTRAINT FK_4EA31EEF81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_4EA31EEF81C06096 ON signup (activity_id)');
    }
}
