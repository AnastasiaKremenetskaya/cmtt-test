<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220121142659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create ads table';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable("ads");
        $table->addColumn('id', 'integer', [
            'autoincrement' => true,
        ]);
        $table->addColumn("text", "text");
        $table->addColumn("price", "integer");
        $table->addColumn("limit", "integer");
        $table->addColumn("banner", "string", [
            'notnull' => false,
        ]);
        $table->setPrimaryKey(array('id'));
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('ads');
    }
}
