<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231016183713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE responses (id INT AUTO_INCREMENT NOT NULL, avis_id INT NOT NULL, marque_id INT NOT NULL, content LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_3E7B0BFB197E709F (avis_id), INDEX IDX_3E7B0BFB4827B9B2 (marque_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE responses ADD CONSTRAINT FK_3E7B0BFB197E709F FOREIGN KEY (avis_id) REFERENCES avis (id)');
        $this->addSql('ALTER TABLE responses ADD CONSTRAINT FK_3E7B0BFB4827B9B2 FOREIGN KEY (marque_id) REFERENCES marques (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE responses DROP FOREIGN KEY FK_3E7B0BFB197E709F');
        $this->addSql('ALTER TABLE responses DROP FOREIGN KEY FK_3E7B0BFB4827B9B2');
        $this->addSql('DROP TABLE responses');
    }
}
