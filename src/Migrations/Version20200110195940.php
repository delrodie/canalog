<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200110195940 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, mode_id INT DEFAULT NULL, officine_id INT NOT NULL, date DATE DEFAULT NULL, montant INT DEFAULT NULL, INDEX IDX_1981A66DC54C8C93 (type_id), INDEX IDX_1981A66D77E5854A (mode_id), INDEX IDX_1981A66DB2D03E4E (officine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D77E5854A FOREIGN KEY (mode_id) REFERENCES mode (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DB2D03E4E FOREIGN KEY (officine_id) REFERENCES officine (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE operation');
    }
}
