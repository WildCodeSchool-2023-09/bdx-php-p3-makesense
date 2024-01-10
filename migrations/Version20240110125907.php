<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110125907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision_user DROP FOREIGN KEY FK_CA157538BDEE7539');
        $this->addSql('ALTER TABLE decision_user ADD CONSTRAINT FK_CA157538BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE opinion ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B027F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_AB02B027F675F31B ON opinion (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B027F675F31B');
        $this->addSql('DROP INDEX IDX_AB02B027F675F31B ON opinion');
        $this->addSql('ALTER TABLE opinion DROP author_id');
        $this->addSql('ALTER TABLE decision_user DROP FOREIGN KEY FK_CA157538BDEE7539');
        $this->addSql('ALTER TABLE decision_user ADD CONSTRAINT FK_CA157538BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
