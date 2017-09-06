<?php
/**
 * @author   Natan Felles <natanfelles@gmail.com>
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migration_create_table_api_access
 *
 * @property CI_DB_forge         $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_create_table_api_access extends CI_Migration {


	public function up()
	{
		$table = 'access';
		$fields = array(
			'id'            => [
				'type'           => 'INT(11)',
				'auto_increment' => TRUE,
				'unsigned'       => TRUE,
			],
			'key'           => [
				'type' => 'VARCHAR(40)',
			],
			'all_access'    => [
				'type'    => 'TINYINT(1)',
				'default' => 0,
			],
			'controller'    => [
				'type' => 'VARCHAR(50)',
			],
			'date_created'  => [
				'type' => 'DATETIME',
				'null' => TRUE,
			],
			'date_modified' => [
				'type' => 'TIMESTAMP',
			],
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('controller');
		$this->dbforge->create_table($table);
		/*$this->db->query(add_foreign_key($table, 'key',
			'keys' . '(key)', 'CASCADE', 'CASCADE'));*/
	}


	public function down()
	{
		$table = 'access';
		if ($this->db->table_exists($table))
		{
			/*$this->db->query(drop_foreign_key($table, 'key'));*/
			$this->dbforge->drop_table($table);
		}
	}

}
