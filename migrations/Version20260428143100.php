<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260428143100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial schema for conference scheduler';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE conference (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(160) NOT NULL, description CLOB NOT NULL, venue VARCHAR(160) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, status VARCHAR(40) NOT NULL)');
        $this->addSql('CREATE TABLE room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, location VARCHAR(120) NOT NULL, capacity INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE speaker (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, full_name VARCHAR(140) NOT NULL, expertise VARCHAR(160) NOT NULL, biography CLOB NOT NULL, avatar_url VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
, password VARCHAR(255) NOT NULL, full_name VARCHAR(120) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE TABLE session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, conference_id INTEGER NOT NULL, room_id INTEGER NOT NULL, title VARCHAR(160) NOT NULL, description CLOB NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, track VARCHAR(40) NOT NULL, status VARCHAR(40) NOT NULL, CONSTRAINT FK_D044D5D4604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D044D5D454177093 FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D044D5D4604B8382 ON session (conference_id)');
        $this->addSql('CREATE INDEX IDX_D044D5D454177093 ON session (room_id)');
        $this->addSql('CREATE TABLE registrations (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, session_id INTEGER NOT NULL, registered_at DATETIME NOT NULL, status VARCHAR(40) NOT NULL, CONSTRAINT FK_4C71C51F76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4C71C51F613FECDF FOREIGN KEY (session_id) REFERENCES session (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX unique_user_session ON registrations (user_id, session_id)');
        $this->addSql('CREATE INDEX IDX_4C71C51FA76ED395 ON registrations (user_id)');
        $this->addSql('CREATE INDEX IDX_4C71C51F613FECDF ON registrations (session_id)');
        $this->addSql('CREATE TABLE session_speakers (session_id INTEGER NOT NULL, speaker_id INTEGER NOT NULL, PRIMARY KEY(session_id, speaker_id), CONSTRAINT FK_B3C43D4F613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B3C43D4F73D4E3E5 FOREIGN KEY (speaker_id) REFERENCES speaker (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B3C43D4F613FECDF ON session_speakers (session_id)');
        $this->addSql('CREATE INDEX IDX_B3C43D4F73D4E3E5 ON session_speakers (speaker_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE session_speakers');
        $this->addSql('DROP TABLE registrations');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE speaker');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE conference');
        $this->addSql('DROP TABLE users');
    }
}
