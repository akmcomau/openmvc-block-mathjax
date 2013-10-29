<?php

namespace modules\block_mathjax\classes\models;

use core\classes\Model;

class BlockMathJax extends Model {

	protected $table       = 'block_mathjax';
	protected $primary_key = 'block_mathjax_id';
	protected $columns     = [
		'block_mathjax_id' => [
			'data_type'      => 'int',
			'auto_increment' => TRUE,
			'null_allowed'   => FALSE,
		],
		'block_id' => [
			'data_type'      => 'int',
			'null_allowed'   => FALSE,
		],
		'block_mathjax_content' => [
			'data_type'      => 'text',
			'null_allowed'   => FALSE,
		],
	];

	protected $indexes = [
		'block_id',
	];

	protected $foreign_keys = [
		'block_id' => ['block', 'block_id'],
	];

	protected $relationships = [
		'__common_join__' => 'JOIN block USING (block_id) LEFT JOIN block_category_link USING (block_id) LEFT JOIN block_category USING (block_category_id)',
		'block' => [
			'where_fields'  => ['block_title', 'block_tag'],
		],
		'block_category' => [
			'where_fields'  => ['block_category_id'],
		],
	];

	public function getBlock() {
		// object is not in the database
		if (!$this->id) {
			return NULL;
		}

		if (!isset($this->objects['block'])) {
			$this->objects['block'] = $this->getModel('\\core\\classes\\models\\Block')->get(['id' => $this->id]);
		}

		return $this->objects['block'];
	}
}
