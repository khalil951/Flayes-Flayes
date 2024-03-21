<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240320225010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bet CHANGE Idevent Idevent INT DEFAULT NULL, CHANGE Iduser Iduser INT DEFAULT NULL');
        $this->addSql('ALTER TABLE event CHANGE idcat idcat INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY fk_fundingID_offerID');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY fk_projectID_offerID');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY fk_userID_offerID');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E9D70482 FOREIGN KEY (funding_id) REFERENCES funding (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873E166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT FK_29D6873EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post CHANGE room_id room_id INT DEFAULT NULL, CHANGE NumLikes NumLikes INT NOT NULL, CHANGE NumDislikes NumDislikes INT NOT NULL');
        $this->addSql('ALTER TABLE postreact CHANGE post_id post_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY fk_userID_id');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reclamation CHANGE id_rec id_rec INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE results CHANGE Idevent Idevent INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket CHANGE Idevent Idevent INT DEFAULT NULL, CHANGE Iduser Iduser INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE bet CHANGE Idevent Idevent INT NOT NULL, CHANGE Iduser Iduser INT NOT NULL');
        $this->addSql('ALTER TABLE event CHANGE idcat idcat INT NOT NULL');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E9D70482');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E166D1F9C');
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873EA76ED395');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT fk_fundingID_offerID FOREIGN KEY (funding_id) REFERENCES funding (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT fk_projectID_offerID FOREIGN KEY (project_id) REFERENCES project (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offer ADD CONSTRAINT fk_userID_offerID FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post CHANGE room_id room_id INT NOT NULL, CHANGE NumLikes NumLikes INT DEFAULT 0 NOT NULL, CHANGE NumDislikes NumDislikes INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE postreact CHANGE user_id user_id INT NOT NULL, CHANGE post_id post_id INT NOT NULL');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA76ED395');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT fk_userID_id FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation CHANGE id_rec id_rec INT NOT NULL');
        $this->addSql('ALTER TABLE results CHANGE Idevent Idevent INT NOT NULL');
        $this->addSql('ALTER TABLE ticket CHANGE Idevent Idevent INT NOT NULL, CHANGE Iduser Iduser INT NOT NULL');
    }
}
