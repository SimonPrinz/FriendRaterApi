<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201019042301 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'initial migration';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL, username VARCHAR(16) NOT NULL, password VARCHAR(1024) NOT NULL, email VARCHAR(512) NOT NULL, firstname VARCHAR(64) NOT NULL, lastname VARCHAR(64) NOT NULL, phone_number VARCHAR(32) DEFAULT NULL, picture VARCHAR(4096) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_comment (id BINARY(16) NOT NULL, user_id BINARY(16) DEFAULT NULL, author_id BINARY(16) DEFAULT NULL, is_anonymous TINYINT(1) NOT NULL, comment VARCHAR(2048) NOT NULL, INDEX IDX_CC794C66A76ED395 (user_id), INDEX IDX_CC794C66F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_comment_rating (id BINARY(16) NOT NULL, user_comment_id BINARY(16) DEFAULT NULL, author_id BINARY(16) DEFAULT NULL, type ENUM(\'0\', \'1\', \'2\') NOT NULL COMMENT \'(DC2Type:RatingType)\', INDEX IDX_791FA1695F0EBBFF (user_comment_id), INDEX IDX_791FA169F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_rating (id BINARY(16) NOT NULL, user_id BINARY(16) DEFAULT NULL, author_id BINARY(16) DEFAULT NULL, type ENUM(\'0\', \'1\', \'2\') NOT NULL COMMENT \'(DC2Type:RatingType)\', INDEX IDX_BDDB3D1FA76ED395 (user_id), INDEX IDX_BDDB3D1FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_comment ADD CONSTRAINT FK_CC794C66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_comment ADD CONSTRAINT FK_CC794C66F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_comment_rating ADD CONSTRAINT FK_791FA1695F0EBBFF FOREIGN KEY (user_comment_id) REFERENCES user_comment (id)');
        $this->addSql('ALTER TABLE user_comment_rating ADD CONSTRAINT FK_791FA169F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_rating ADD CONSTRAINT FK_BDDB3D1FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_rating ADD CONSTRAINT FK_BDDB3D1FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE user_comment DROP FOREIGN KEY FK_CC794C66A76ED395');
        $this->addSql('ALTER TABLE user_comment DROP FOREIGN KEY FK_CC794C66F675F31B');
        $this->addSql('ALTER TABLE user_comment_rating DROP FOREIGN KEY FK_791FA169F675F31B');
        $this->addSql('ALTER TABLE user_rating DROP FOREIGN KEY FK_BDDB3D1FA76ED395');
        $this->addSql('ALTER TABLE user_rating DROP FOREIGN KEY FK_BDDB3D1FF675F31B');
        $this->addSql('ALTER TABLE user_comment_rating DROP FOREIGN KEY FK_791FA1695F0EBBFF');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_comment');
        $this->addSql('DROP TABLE user_comment_rating');
        $this->addSql('DROP TABLE user_rating');
    }
}
