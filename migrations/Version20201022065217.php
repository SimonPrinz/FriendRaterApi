<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201022065217 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'add dates';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL DEFAULT NOW(), ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_comment ADD created_at DATETIME NOT NULL DEFAULT NOW(), ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_comment_rating ADD created_at DATETIME NOT NULL DEFAULT NOW(), ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_rating ADD created_at DATETIME NOT NULL DEFAULT NOW(), ADD updated_at DATETIME DEFAULT NULL');

        $this->addSql('ALTER TABLE user CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user_comment CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user_comment_rating CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user_rating CHANGE created_at created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE user DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE user_comment DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE user_comment_rating DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE user_rating DROP created_at, DROP updated_at');
    }
}
