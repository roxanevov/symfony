<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180206102016 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `show` ADD category_id INT DEFAULT NULL, ADD name VARCHAR(255) NOT NULL, ADD abstract LONGTEXT NOT NULL, ADD country VARCHAR(255) NOT NULL, ADD author VARCHAR(255) NOT NULL, ADD released_date DATE NOT NULL, ADD main_picture VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `show` ADD CONSTRAINT FK_320ED90112469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_320ED90112469DE2 ON `show` (category_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category DROP name');
        $this->addSql('ALTER TABLE `show` DROP FOREIGN KEY FK_320ED90112469DE2');
        $this->addSql('DROP INDEX IDX_320ED90112469DE2 ON `show`');
        $this->addSql('ALTER TABLE `show` DROP category_id, DROP name, DROP abstract, DROP country, DROP author, DROP released_date, DROP main_picture');
    }
}
