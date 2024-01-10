<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110155803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE decision (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, description LONGTEXT NOT NULL, impact LONGTEXT NOT NULL, context LONGTEXT NOT NULL, benefits LONGTEXT NOT NULL, risk LONGTEXT NOT NULL, deadline_decision DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', deadline_conflict TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', deadline_final TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', starting_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', deadline_opinion DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decision_user (decision_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_CA157538BDEE7539 (decision_id), INDEX IDX_CA157538A76ED395 (user_id), PRIMARY KEY(decision_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE decision_group (decision_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_4A8583CCBDEE7539 (decision_id), INDEX IDX_4A8583CCFE54D947 (group_id), PRIMARY KEY(decision_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favori (id INT AUTO_INCREMENT NOT NULL, decision_id INT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_EF85A2CCBDEE7539 (decision_id), INDEX IDX_EF85A2CCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opinion (id INT AUTO_INCREMENT NOT NULL, decision_id INT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_AB02B027BDEE7539 (decision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, phone_number INT NOT NULL, city VARCHAR(50) NOT NULL, occupation VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, photo VARCHAR(255) NOT NULL, reseau VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_8F02BF9DA76ED395 (user_id), INDEX IDX_8F02BF9DFE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE decision_user ADD CONSTRAINT FK_CA157538BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_user ADD CONSTRAINT FK_CA157538A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_group ADD CONSTRAINT FK_4A8583CCBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE decision_group ADD CONSTRAINT FK_4A8583CCFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CCBDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE favori ADD CONSTRAINT FK_EF85A2CCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE opinion ADD CONSTRAINT FK_AB02B027BDEE7539 FOREIGN KEY (decision_id) REFERENCES decision (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE decision_user DROP FOREIGN KEY FK_CA157538BDEE7539');
        $this->addSql('ALTER TABLE decision_user DROP FOREIGN KEY FK_CA157538A76ED395');
        $this->addSql('ALTER TABLE decision_group DROP FOREIGN KEY FK_4A8583CCBDEE7539');
        $this->addSql('ALTER TABLE decision_group DROP FOREIGN KEY FK_4A8583CCFE54D947');
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CCBDEE7539');
        $this->addSql('ALTER TABLE favori DROP FOREIGN KEY FK_EF85A2CCA76ED395');
        $this->addSql('ALTER TABLE opinion DROP FOREIGN KEY FK_AB02B027BDEE7539');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9DA76ED395');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9DFE54D947');
        $this->addSql('DROP TABLE decision');
        $this->addSql('DROP TABLE decision_user');
        $this->addSql('DROP TABLE decision_group');
        $this->addSql('DROP TABLE favori');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE opinion');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
