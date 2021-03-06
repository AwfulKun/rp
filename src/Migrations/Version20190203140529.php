<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190203140529 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON app_user');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON app_user');
        $this->addSql('ALTER TABLE app_user ADD roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE rp CHANGE app_user_id app_user_id INT DEFAULT NULL, CHANGE status_id status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rp_has_app_character RENAME INDEX fk_rp_has_app_character_rp_idx TO IDX_4045FDA4B70FF80C');
        $this->addSql('ALTER TABLE rp_has_app_character RENAME INDEX fk_rp_has_app_character_app_character1_idx TO IDX_4045FDA4D3B8A4F4');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_user DROP roles');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON app_user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON app_user (username)');
        $this->addSql('ALTER TABLE rp CHANGE app_user_id app_user_id INT NOT NULL, CHANGE status_id status_id INT NOT NULL');
        $this->addSql('ALTER TABLE rp_has_app_character RENAME INDEX idx_4045fda4d3b8a4f4 TO fk_rp_has_app_character_app_character1_idx');
        $this->addSql('ALTER TABLE rp_has_app_character RENAME INDEX idx_4045fda4b70ff80c TO fk_rp_has_app_character_rp_idx');
    }
}
