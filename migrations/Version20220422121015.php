<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220422121015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trajet ADD ville_dep_id INT DEFAULT NULL, ADD ville_arr_id INT DEFAULT NULL, ADD pers_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98C97A9E2C6 FOREIGN KEY (ville_dep_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98CBFADF06C FOREIGN KEY (ville_arr_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98C4AA53143 FOREIGN KEY (pers_id) REFERENCES personne (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2B5BA98C97A9E2C6 ON trajet (ville_dep_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2B5BA98CBFADF06C ON trajet (ville_arr_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2B5BA98C4AA53143 ON trajet (pers_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE marque CHANGE nom nom VARCHAR(50) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE personne CHANGE nom nom VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prenom prenom VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE tel tel VARCHAR(10) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98C97A9E2C6');
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98CBFADF06C');
        $this->addSql('ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98C4AA53143');
        $this->addSql('DROP INDEX UNIQ_2B5BA98C97A9E2C6 ON trajet');
        $this->addSql('DROP INDEX UNIQ_2B5BA98CBFADF06C ON trajet');
        $this->addSql('DROP INDEX UNIQ_2B5BA98C4AA53143 ON trajet');
        $this->addSql('ALTER TABLE trajet DROP ville_dep_id, DROP ville_arr_id, DROP pers_id');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE api_token api_token VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE voiture CHANGE modele modele VARCHAR(30) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
