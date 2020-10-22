<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201022023733 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'user activation';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE user ADD activate_until DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE user DROP activate_until');
    }
}
