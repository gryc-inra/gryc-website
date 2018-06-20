<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180226074743 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', enabled TINYINT(1) NOT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, session_id VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE strain (id INT AUTO_INCREMENT NOT NULL, species_id INT NOT NULL, name VARCHAR(255) NOT NULL, synonymes LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', length INT NOT NULL, gc DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, cdsCount INT NOT NULL, slug VARCHAR(128) NOT NULL, public TINYINT(1) NOT NULL, typeStrain TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A630CD725E237E06 (name), UNIQUE INDEX UNIQ_A630CD72989D9B62 (slug), INDEX IDX_A630CD72B2A1D860 (species_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE strain_user (strain_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_BB5DDD969B9E007 (strain_id), INDEX IDX_BB5DDD9A76ED395 (user_id), PRIMARY KEY(strain_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blast (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, command_line LONGTEXT DEFAULT NULL, output LONGTEXT DEFAULT NULL, error_output LONGTEXT DEFAULT NULL, exit_code INT DEFAULT NULL, created DATETIME NOT NULL, tool VARCHAR(255) NOT NULL, db VARCHAR(255) NOT NULL, query LONGTEXT NOT NULL, filter TINYINT(1) NOT NULL, evalue DOUBLE PRECISION NOT NULL, gapped TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_4E4FA58C5E237E06 (name), INDEX IDX_4E4FA58CDE12AB56 (created_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blast_strain (blast_id INT NOT NULL, strain_id INT NOT NULL, INDEX IDX_F80CCABCBB7D3337 (blast_id), INDEX IDX_F80CCABC69B9E007 (strain_id), PRIMARY KEY(blast_id, strain_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE clade (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, mainClade TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1C1C28585E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE species (id INT AUTO_INCREMENT NOT NULL, clade_id INT NOT NULL, scientificName VARCHAR(255) NOT NULL, genus VARCHAR(255) NOT NULL, species VARCHAR(255) NOT NULL, lineages LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', tax_id INT DEFAULT NULL, geneticCode INT NOT NULL, mitoCode INT NOT NULL, synonyms LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', description LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_A50FF712B6B0C54 (scientificName), UNIQUE INDEX UNIQ_A50FF712A50FF712 (species), UNIQUE INDEX UNIQ_A50FF712B2A824D8 (tax_id), UNIQUE INDEX UNIQ_A50FF712989D9B62 (slug), INDEX IDX_A50FF7124DB248C1 (clade_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, chromosome_id INT DEFAULT NULL, strain_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8C9F3610B548B0F (path), UNIQUE INDEX UNIQ_8C9F3610989D9B62 (slug), INDEX IDX_8C9F3610602E5519 (chromosome_id), INDEX IDX_8C9F361069B9E007 (strain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE neighbour (id INT AUTO_INCREMENT NOT NULL, locus_id INT NOT NULL, neighbour_id INT DEFAULT NULL, position INT NOT NULL, number_neighbours INT NOT NULL, INDEX IDX_76D9C429C040578A (locus_id), INDEX IDX_76D9C429144C013 (neighbour_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, feature_id INT NOT NULL, strand SMALLINT NOT NULL, product LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, dbXref LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', annotation LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', type VARCHAR(255) NOT NULL, coordinates LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', start INT NOT NULL, end INT NOT NULL, note LONGTEXT DEFAULT NULL, translation LONGTEXT DEFAULT NULL, structure LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_D34A04AD989D9B62 (slug), INDEX IDX_D34A04AD60E4B879 (feature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reference (id INT AUTO_INCREMENT NOT NULL, authors LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', container VARCHAR(255) NOT NULL, doi VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, issued INT NOT NULL, UNIQUE INDEX UNIQ_AEA349136694147A (doi), UNIQUE INDEX UNIQ_AEA34913F47645AE (url), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reference_locus (reference_id INT NOT NULL, locus_id INT NOT NULL, INDEX IDX_FED132581645DEA9 (reference_id), INDEX IDX_FED13258C040578A (locus_id), PRIMARY KEY(reference_id, locus_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reference_strain (reference_id INT NOT NULL, strain_id INT NOT NULL, INDEX IDX_BB517C761645DEA9 (reference_id), INDEX IDX_BB517C7669B9E007 (strain_id), PRIMARY KEY(reference_id, strain_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feature (id INT AUTO_INCREMENT NOT NULL, locus_id INT NOT NULL, strand SMALLINT NOT NULL, product LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, dbXref LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', annotation LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', type VARCHAR(255) NOT NULL, coordinates LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', start INT NOT NULL, end INT NOT NULL, note LONGTEXT DEFAULT NULL, structure LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_1FD77566989D9B62 (slug), INDEX IDX_1FD77566C040578A (locus_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chromosome (id INT AUTO_INCREMENT NOT NULL, strain_id INT NOT NULL, dna_sequence_id INT NOT NULL, name VARCHAR(255) NOT NULL, accessions LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', description VARCHAR(255) NOT NULL, keywords LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', projectId VARCHAR(255) DEFAULT NULL, dateCreated DATETIME NOT NULL, numCreated INT DEFAULT NULL, dateReleased DATETIME NOT NULL, numReleased INT DEFAULT NULL, numVersion INT DEFAULT NULL, length INT NOT NULL, gc DOUBLE PRECISION NOT NULL, cdsCount INT NOT NULL, mitochondrial TINYINT(1) NOT NULL, comment LONGTEXT DEFAULT NULL, slug VARCHAR(128) NOT NULL, source VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D5E8A112989D9B62 (slug), INDEX IDX_D5E8A11269B9E007 (strain_id), UNIQUE INDEX UNIQ_D5E8A1123621972A (dna_sequence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seo (id INT AUTO_INCREMENT NOT NULL, strain_id INT DEFAULT NULL, species_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_6C71EC3069B9E007 (strain_id), INDEX IDX_6C71EC30B2A1D860 (species_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locus (id INT AUTO_INCREMENT NOT NULL, chromosome_id INT NOT NULL, strand SMALLINT NOT NULL, product LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, dbXref LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', annotation LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', type VARCHAR(255) NOT NULL, coordinates LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', start INT NOT NULL, end INT NOT NULL, note LONGTEXT DEFAULT NULL, context VARCHAR(255) NOT NULL, sequence LONGTEXT NOT NULL, upstream_sequence LONGTEXT NOT NULL, downstream_sequence LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_28705248989D9B62 (slug), INDEX IDX_28705248602E5519 (chromosome_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dna_sequence (id INT AUTO_INCREMENT NOT NULL, letterCount LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE multiple_alignment (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, command_line LONGTEXT DEFAULT NULL, output LONGTEXT DEFAULT NULL, error_output LONGTEXT DEFAULT NULL, exit_code INT DEFAULT NULL, created DATETIME NOT NULL, query LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_E75ECBA05E237E06 (name), INDEX IDX_E75ECBA0DE12AB56 (created_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dbxref (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, pattern VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, source VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_84F0A7005E237E06 (name), UNIQUE INDEX UNIQ_84F0A700A3BCFC8E (pattern), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE strain ADD CONSTRAINT FK_A630CD72B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id)');
        $this->addSql('ALTER TABLE strain_user ADD CONSTRAINT FK_BB5DDD969B9E007 FOREIGN KEY (strain_id) REFERENCES strain (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE strain_user ADD CONSTRAINT FK_BB5DDD9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blast ADD CONSTRAINT FK_4E4FA58CDE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blast_strain ADD CONSTRAINT FK_F80CCABCBB7D3337 FOREIGN KEY (blast_id) REFERENCES blast (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blast_strain ADD CONSTRAINT FK_F80CCABC69B9E007 FOREIGN KEY (strain_id) REFERENCES strain (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE species ADD CONSTRAINT FK_A50FF7124DB248C1 FOREIGN KEY (clade_id) REFERENCES clade (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610602E5519 FOREIGN KEY (chromosome_id) REFERENCES chromosome (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361069B9E007 FOREIGN KEY (strain_id) REFERENCES strain (id)');
        $this->addSql('ALTER TABLE neighbour ADD CONSTRAINT FK_76D9C429C040578A FOREIGN KEY (locus_id) REFERENCES locus (id)');
        $this->addSql('ALTER TABLE neighbour ADD CONSTRAINT FK_76D9C429144C013 FOREIGN KEY (neighbour_id) REFERENCES locus (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD60E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id)');
        $this->addSql('ALTER TABLE reference_locus ADD CONSTRAINT FK_FED132581645DEA9 FOREIGN KEY (reference_id) REFERENCES reference (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reference_locus ADD CONSTRAINT FK_FED13258C040578A FOREIGN KEY (locus_id) REFERENCES locus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reference_strain ADD CONSTRAINT FK_BB517C761645DEA9 FOREIGN KEY (reference_id) REFERENCES reference (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reference_strain ADD CONSTRAINT FK_BB517C7669B9E007 FOREIGN KEY (strain_id) REFERENCES strain (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE feature ADD CONSTRAINT FK_1FD77566C040578A FOREIGN KEY (locus_id) REFERENCES locus (id)');
        $this->addSql('ALTER TABLE chromosome ADD CONSTRAINT FK_D5E8A11269B9E007 FOREIGN KEY (strain_id) REFERENCES strain (id)');
        $this->addSql('ALTER TABLE chromosome ADD CONSTRAINT FK_D5E8A1123621972A FOREIGN KEY (dna_sequence_id) REFERENCES dna_sequence (id)');
        $this->addSql('ALTER TABLE seo ADD CONSTRAINT FK_6C71EC3069B9E007 FOREIGN KEY (strain_id) REFERENCES strain (id)');
        $this->addSql('ALTER TABLE seo ADD CONSTRAINT FK_6C71EC30B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id)');
        $this->addSql('ALTER TABLE locus ADD CONSTRAINT FK_28705248602E5519 FOREIGN KEY (chromosome_id) REFERENCES chromosome (id)');
        $this->addSql('ALTER TABLE multiple_alignment ADD CONSTRAINT FK_E75ECBA0DE12AB56 FOREIGN KEY (created_by) REFERENCES user (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE strain_user DROP FOREIGN KEY FK_BB5DDD9A76ED395');
        $this->addSql('ALTER TABLE blast DROP FOREIGN KEY FK_4E4FA58CDE12AB56');
        $this->addSql('ALTER TABLE multiple_alignment DROP FOREIGN KEY FK_E75ECBA0DE12AB56');
        $this->addSql('ALTER TABLE strain_user DROP FOREIGN KEY FK_BB5DDD969B9E007');
        $this->addSql('ALTER TABLE blast_strain DROP FOREIGN KEY FK_F80CCABC69B9E007');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F361069B9E007');
        $this->addSql('ALTER TABLE reference_strain DROP FOREIGN KEY FK_BB517C7669B9E007');
        $this->addSql('ALTER TABLE chromosome DROP FOREIGN KEY FK_D5E8A11269B9E007');
        $this->addSql('ALTER TABLE seo DROP FOREIGN KEY FK_6C71EC3069B9E007');
        $this->addSql('ALTER TABLE blast_strain DROP FOREIGN KEY FK_F80CCABCBB7D3337');
        $this->addSql('ALTER TABLE species DROP FOREIGN KEY FK_A50FF7124DB248C1');
        $this->addSql('ALTER TABLE strain DROP FOREIGN KEY FK_A630CD72B2A1D860');
        $this->addSql('ALTER TABLE seo DROP FOREIGN KEY FK_6C71EC30B2A1D860');
        $this->addSql('ALTER TABLE reference_locus DROP FOREIGN KEY FK_FED132581645DEA9');
        $this->addSql('ALTER TABLE reference_strain DROP FOREIGN KEY FK_BB517C761645DEA9');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD60E4B879');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610602E5519');
        $this->addSql('ALTER TABLE locus DROP FOREIGN KEY FK_28705248602E5519');
        $this->addSql('ALTER TABLE neighbour DROP FOREIGN KEY FK_76D9C429C040578A');
        $this->addSql('ALTER TABLE neighbour DROP FOREIGN KEY FK_76D9C429144C013');
        $this->addSql('ALTER TABLE reference_locus DROP FOREIGN KEY FK_FED13258C040578A');
        $this->addSql('ALTER TABLE feature DROP FOREIGN KEY FK_1FD77566C040578A');
        $this->addSql('ALTER TABLE chromosome DROP FOREIGN KEY FK_D5E8A1123621972A');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE strain');
        $this->addSql('DROP TABLE strain_user');
        $this->addSql('DROP TABLE blast');
        $this->addSql('DROP TABLE blast_strain');
        $this->addSql('DROP TABLE clade');
        $this->addSql('DROP TABLE species');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE neighbour');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE reference');
        $this->addSql('DROP TABLE reference_locus');
        $this->addSql('DROP TABLE reference_strain');
        $this->addSql('DROP TABLE feature');
        $this->addSql('DROP TABLE chromosome');
        $this->addSql('DROP TABLE seo');
        $this->addSql('DROP TABLE locus');
        $this->addSql('DROP TABLE dna_sequence');
        $this->addSql('DROP TABLE multiple_alignment');
        $this->addSql('DROP TABLE dbxref');
    }
}
