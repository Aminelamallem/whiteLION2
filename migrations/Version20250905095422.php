<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250905095422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_category (product_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_CDFC73564584665A (product_id), INDEX IDX_CDFC735612469DE2 (category_id), PRIMARY KEY(product_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_material (product_id INT NOT NULL, material_id INT NOT NULL, INDEX IDX_B70E1F024584665A (product_id), INDEX IDX_B70E1F02E308AC6F (material_id), PRIMARY KEY(product_id, material_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_variant_size (product_variant_id INT NOT NULL, size_id INT NOT NULL, INDEX IDX_959C9094A80EF684 (product_variant_id), INDEX IDX_959C9094498DA827 (size_id), PRIMARY KEY(product_variant_id, size_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_variant_color (product_variant_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_3C31CB46A80EF684 (product_variant_id), INDEX IDX_3C31CB467ADA1FB5 (color_id), PRIMARY KEY(product_variant_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_variant_gender (product_variant_id INT NOT NULL, gender_id INT NOT NULL, INDEX IDX_8174D3B8A80EF684 (product_variant_id), INDEX IDX_8174D3B8708A0E0 (gender_id), PRIMARY KEY(product_variant_id, gender_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_variant_order (product_variant_id INT NOT NULL, order_id INT NOT NULL, INDEX IDX_AF4E1037A80EF684 (product_variant_id), INDEX IDX_AF4E10378D9F6D38 (order_id), PRIMARY KEY(product_variant_id, order_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC73564584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category ADD CONSTRAINT FK_CDFC735612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_material ADD CONSTRAINT FK_B70E1F024584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_material ADD CONSTRAINT FK_B70E1F02E308AC6F FOREIGN KEY (material_id) REFERENCES material (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant_size ADD CONSTRAINT FK_959C9094A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant_size ADD CONSTRAINT FK_959C9094498DA827 FOREIGN KEY (size_id) REFERENCES size (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant_color ADD CONSTRAINT FK_3C31CB46A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant_color ADD CONSTRAINT FK_3C31CB467ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant_gender ADD CONSTRAINT FK_8174D3B8A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant_gender ADD CONSTRAINT FK_8174D3B8708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant_order ADD CONSTRAINT FK_AF4E1037A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant_order ADD CONSTRAINT FK_AF4E10378D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `order` ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F5299398A76ED395 ON `order` (user_id)');
        $this->addSql('ALTER TABLE product_variant ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_209AA41D4584665A ON product_variant (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC73564584665A');
        $this->addSql('ALTER TABLE product_category DROP FOREIGN KEY FK_CDFC735612469DE2');
        $this->addSql('ALTER TABLE product_material DROP FOREIGN KEY FK_B70E1F024584665A');
        $this->addSql('ALTER TABLE product_material DROP FOREIGN KEY FK_B70E1F02E308AC6F');
        $this->addSql('ALTER TABLE product_variant_size DROP FOREIGN KEY FK_959C9094A80EF684');
        $this->addSql('ALTER TABLE product_variant_size DROP FOREIGN KEY FK_959C9094498DA827');
        $this->addSql('ALTER TABLE product_variant_color DROP FOREIGN KEY FK_3C31CB46A80EF684');
        $this->addSql('ALTER TABLE product_variant_color DROP FOREIGN KEY FK_3C31CB467ADA1FB5');
        $this->addSql('ALTER TABLE product_variant_gender DROP FOREIGN KEY FK_8174D3B8A80EF684');
        $this->addSql('ALTER TABLE product_variant_gender DROP FOREIGN KEY FK_8174D3B8708A0E0');
        $this->addSql('ALTER TABLE product_variant_order DROP FOREIGN KEY FK_AF4E1037A80EF684');
        $this->addSql('ALTER TABLE product_variant_order DROP FOREIGN KEY FK_AF4E10378D9F6D38');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE product_material');
        $this->addSql('DROP TABLE product_variant_size');
        $this->addSql('DROP TABLE product_variant_color');
        $this->addSql('DROP TABLE product_variant_gender');
        $this->addSql('DROP TABLE product_variant_order');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('DROP INDEX IDX_F5299398A76ED395 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP user_id');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D4584665A');
        $this->addSql('DROP INDEX IDX_209AA41D4584665A ON product_variant');
        $this->addSql('ALTER TABLE product_variant DROP product_id');
    }
}
