<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231203091705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add StudyProgramme table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE app_study_programmes (id UUID NOT NULL, name JSON NOT NULL, type VARCHAR(255) NOT NULL, number_of_semesters INT NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_259379FE77153098 ON app_study_programmes (code)');
        $this->addSql('COMMENT ON COLUMN app_study_programmes.id IS \'(DC2Type:study_programme_uuid)\'');
        $this->addSql('COMMENT ON COLUMN app_study_programmes.name IS \'(DC2Type:translation_vo)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE app_study_programmes');
    }
}
