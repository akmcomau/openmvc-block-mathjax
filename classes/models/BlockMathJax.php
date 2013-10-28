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
}
