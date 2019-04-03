<?php declare(strict_types=1);

namespace app\Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181022055831 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $recipesTable = $schema->createTable('recipes');
        $recipesTable->addColumn('recipe_id', 'integer', array('unsigned' => true, 'autoincrement'=>true));
        $recipesTable->addColumn('name', 'string', array('length' => 255));
        $recipesTable->addColumn('prep_time', 'integer');
        $recipesTable->addColumn('difficulty', 'smallint');
        $recipesTable->addColumn('vegetarian', 'boolean');
        $recipesTable->setPrimaryKey(array('recipe_id'));

        $recipesRateTable = $schema->createTable('recipes_rate');
        $recipesRateTable->addColumn('rate_id', 'integer', array('unsigned' => true, 'autoincrement'=>true));
        $recipesRateTable->addColumn('recipe_id', 'integer');
        $recipesRateTable->addColumn('rate', 'smallint');
        $recipesRateTable->setPrimaryKey(array('rate_id'));
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('recipes');
        $schema->dropTable('recipes_rate');
    }
}
