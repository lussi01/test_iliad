<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702182628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ordine ADD order_id INT AUTO_INCREMENT NOT NULL, ADD cognome VARCHAR(255) NOT NULL, ADD product_id INT NOT NULL, DROP orderID, DROP descrizione, CHANGE nome nome VARCHAR(255) NOT NULL, CHANGE data data DATETIME NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE ordine MODIFY order_id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON ordine');
        $this->addSql('ALTER TABLE ordine ADD orderID VARCHAR(50) NOT NULL, ADD descrizione VARCHAR(50) DEFAULT \'NULL\', DROP order_id, DROP cognome, DROP product_id, CHANGE nome nome VARCHAR(20) DEFAULT \'NULL\', CHANGE data data DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE ordine ADD PRIMARY KEY (orderID)');
    }
}
