<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503120042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE etablissement (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ville_id INTEGER NOT NULL, nom VARCHAR(150) NOT NULL, slug VARCHAR(255) NOT NULL, description CLOB NOT NULL, telephone VARCHAR(20) NOT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(150) NOT NULL, image VARCHAR(255) DEFAULT NULL, actif BOOLEAN NOT NULL, accueil BOOLEAN NOT NULL, created_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, CONSTRAINT FK_20FD592CA73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_20FD592CA73F0036 ON etablissement (ville_id)');
        $this->addSql('CREATE TABLE etablissement_categorie (etablissement_id INTEGER NOT NULL, categorie_id INTEGER NOT NULL, PRIMARY KEY(etablissement_id, categorie_id), CONSTRAINT FK_24A25B5DFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_24A25B5DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_24A25B5DFF631228 ON etablissement_categorie (etablissement_id)');
        $this->addSql('CREATE INDEX IDX_24A25B5DBCF5E72D ON etablissement_categorie (categorie_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, prenom VARCHAR(70) NOT NULL, nom VARCHAR(70) NOT NULL, pseudo VARCHAR(70) DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME DEFAULT NULL, actif BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE user_etablissement (user_id INTEGER NOT NULL, etablissement_id INTEGER NOT NULL, PRIMARY KEY(user_id, etablissement_id), CONSTRAINT FK_CE73F47CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_CE73F47CFF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CE73F47CA76ED395 ON user_etablissement (user_id)');
        $this->addSql('CREATE INDEX IDX_CE73F47CFF631228 ON user_etablissement (etablissement_id)');
        $this->addSql('CREATE TABLE ville (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(150) NOT NULL, cp VARCHAR(5) NOT NULL, lib_departement VARCHAR(150) NOT NULL, num_departement VARCHAR(10) NOT NULL, lib_region VARCHAR(150) NOT NULL)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE etablissement');
        $this->addSql('DROP TABLE etablissement_categorie');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_etablissement');
        $this->addSql('DROP TABLE ville');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
