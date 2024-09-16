<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240912080050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE signup (id INT AUTO_INCREMENT NOT NULL, child_id INT NOT NULL, activity_id INT NOT NULL, signed_up_at DATETIME NOT NULL, INDEX IDX_4EA31EEFDD62C21B (child_id), INDEX IDX_4EA31EEF81C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE signup ADD CONSTRAINT FK_4EA31EEFDD62C21B FOREIGN KEY (child_id) REFERENCES child (id)');
        $this->addSql('ALTER TABLE signup ADD CONSTRAINT FK_4EA31EEF81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE signup DROP FOREIGN KEY FK_4EA31EEFDD62C21B');
        $this->addSql('ALTER TABLE signup DROP FOREIGN KEY FK_4EA31EEF81C06096');
        $this->addSql('DROP TABLE signup');
    }
}
