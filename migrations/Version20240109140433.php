<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240109140433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decision_group (decision_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_4A8583CCBDEE7539 (decision_id), INDEX IDX_4A8583CCFE54D947 (group_id), PRIMARY KEY(decision_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decision_group ADD CONSTRAINT FK_4A8583CCBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_group ADD CONSTRAINT FK_4A8583CCFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision DROP FOREIGN KEY FK_84ACBE484FFB754B');
        $this->addSql('DROP INDEX IDX_84ACBE484FFB754B ON decision');
        $this->addSql('ALTER TABLE decision DROP groupappelation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision_group DROP FOREIGN KEY FK_4A8583CCBDEE7539');
        $this->addSql('ALTER TABLE decision_group DROP FOREIGN KEY FK_4A8583CCFE54D947');
        $this->addSql('DROP TABLE decision_group');
        $this->addSql('ALTER TABLE decision ADD groupappelation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE decision ADD CONSTRAINT FK_84ACBE484FFB754B FOREIGN KEY (groupappelation_id) REFERENCES `group` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_84ACBE484FFB754B ON decision (groupappelation_id)');
    }
}
