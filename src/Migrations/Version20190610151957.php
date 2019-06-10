<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190610151957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE disposal DROP FOREIGN KEY FK_56D9A1774D16C4DD');
        $this->addSql('DROP INDEX UNIQ_56D9A1774D16C4DD ON disposal');
        $this->addSql('ALTER TABLE disposal DROP shop_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE disposal ADD shop_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE disposal ADD CONSTRAINT FK_56D9A1774D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_56D9A1774D16C4DD ON disposal (shop_id)');
    }
}
