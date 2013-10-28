<?php

namespace modules\block_mathjax;

use ErrorException;
use core\classes\Config;
use core\classes\Database;
use core\classes\Language;
use core\classes\Model;

class Installer {
	protected $config;
	protected $database;

	public function __construct(Config $config, Database $database) {
		$this->config = $config;
		$this->database = $database;
	}

	public function install() {
		$model = new Model($this->config, $this->database);

		// create block_mathjax database table
		$block_mathjax = $model->getModel('\\modules\\block_mathjax\\classes\\models\\BlockMathJax');
		$block_mathjax->createTable();

		// add the MathJax block_type
		$block_type = $model->getModel('\\core\\classes\\models\\BlockType');
		$block_type->name = 'MathJax';
		$block_type->insert();
	}

	public function uninstall() {
		$model = new Model($this->config, $this->database);

		// make all mathjax blocks into normal blocks
		$sql = "
			UPDATE block
			SET block_type_id = (SELECT block_type_id FROM block_type WHERE block_type_name='HTML')
			WHERE block_type_id = (SELECT block_type_id FROM block_type WHERE block_type_name='MathJax')
		";
		$this->database->executeQuery($sql);

		// drop block_mathjax database table
		$block_mathjax = $model->getModel('\\modules\\block_mathjax\\classes\\models\\BlockMathJax');
		$block_mathjax->dropTable();

		// remove the MathJax block_type
		$block_type = $model->getModel('\\core\\classes\\models\\BlockType')->get(['name' => 'MathJax']);
		$block_type->delete();
	}

	public function enable() {
		// Nothing needed here
	}

	public function disable() {
		// Nothing needed here
	}
}