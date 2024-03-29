<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605114727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etablissement (id INT AUTO_INCREMENT NOT NULL, ville_id INT NOT NULL, nom VARCHAR(150) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, telephone VARCHAR(20) NOT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(150) NOT NULL, image VARCHAR(255) DEFAULT NULL, actif TINYINT(1) NOT NULL, accueil TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, INDEX IDX_20FD592CA73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etablissement_categorie (etablissement_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_24A25B5DFF631228 (etablissement_id), INDEX IDX_24A25B5DBCF5E72D (categorie_id), PRIMARY KEY(etablissement_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, prenom VARCHAR(70) NOT NULL, nom VARCHAR(70) NOT NULL, pseudo VARCHAR(70) DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, actif TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_etablissement (user_id INT NOT NULL, etablissement_id INT NOT NULL, INDEX IDX_CE73F47CA76ED395 (user_id), INDEX IDX_CE73F47CFF631228 (etablissement_id), PRIMARY KEY(user_id, etablissement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, cp VARCHAR(5) NOT NULL, lib_departement VARCHAR(150) NOT NULL, num_departement VARCHAR(10) NOT NULL, lib_region VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592CA73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE etablissement_categorie ADD CONSTRAINT FK_24A25B5DFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etablissement_categorie ADD CONSTRAINT FK_24A25B5DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_etablissement ADD CONSTRAINT FK_CE73F47CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_etablissement ADD CONSTRAINT FK_CE73F47CFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592CA73F0036');
        $this->addSql('ALTER TABLE etablissement_categorie DROP FOREIGN KEY FK_24A25B5DFF631228');
        $this->addSql('ALTER TABLE etablissement_categorie DROP FOREIGN KEY FK_24A25B5DBCF5E72D');
        $this->addSql('ALTER TABLE user_etablissement DROP FOREIGN KEY FK_CE73F47CA76ED395');
        $this->addSql('ALTER TABLE user_etablissement DROP FOREIGN KEY FK_CE73F47CFF631228');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE etablissement');
        $this->addSql('DROP TABLE etablissement_categorie');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_etablissement');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
