<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210412122426 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateur_formation (utilisateur_id INT NOT NULL, formation_id INT NOT NULL, INDEX IDX_20EED493FB88E14F (utilisateur_id), INDEX IDX_20EED4935200282E (formation_id), PRIMARY KEY(utilisateur_id, formation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE utilisateur_formation ADD CONSTRAINT FK_20EED493FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur_formation ADD CONSTRAINT FK_20EED4935200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module ADD formation_id INT NOT NULL');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C2426285200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('CREATE INDEX IDX_C2426285200282E ON module (formation_id)');
        $this->addSql('ALTER TABLE seance ADD module_id INT NOT NULL');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0EAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('CREATE INDEX IDX_DF7DFD0EAFC2B591 ON seance (module_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE utilisateur_formation');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C2426285200282E');
        $this->addSql('DROP INDEX IDX_C2426285200282E ON module');
        $this->addSql('ALTER TABLE module DROP formation_id');
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0EAFC2B591');
        $this->addSql('DROP INDEX IDX_DF7DFD0EAFC2B591 ON seance');
        $this->addSql('ALTER TABLE seance DROP module_id');
    }
}
