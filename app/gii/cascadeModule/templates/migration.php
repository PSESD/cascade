<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 *
 * @var string $className the new migration class name
 */
echo "<?php\n";
?>
namespace <?= $migrationsNamespace; ?>;

class <?=$migrationClassName; ?> extends \infinite\db\Migration
{
	public function up()
	{
		$this->dropExistingTable('<?= $tableName; ?>');
		$this->createTable('<?= $tableName; ?>', [<?= $createTableSyntax; ?>
		]);
		
		return $this->execute($sql);
	}



	public function down()
	{
		return $this->dropExistingTable('<?= $tableName; ?>');
	}
}
