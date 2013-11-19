<?php

namespace modules\block_mathjax\classes;

use DOMDocument;
use core\classes\Request;
use core\classes\Config;
use core\classes\Logger;
use core\classes\Database;
use core\classes\Model;
use core\classes\Module;
use core\classes\models\Block;

class Hooks {

	protected $config;
	protected $database;
	protected $request;
	protected $logger;

	public function __construct(Config $config, Database $database, Request $request = NULL) {
		$this->config   = $config;
		$this->database = $database;
		$this->request  = $request;
		$this->logger   = Logger::getLogger(__CLASS__);
	}

	public function block_insert(Block $block) {
		return $this->compile($block);
	}

	public function block_update(Block $block) {
		return $this->compile($block);
	}

	protected function compile(Block $block) {
		$module_config = $this->config->moduleConfig('Block MathJax');
		$phantomjs = $module_config->phantomjs;

		$url = $this->config->getSiteURL().'/admin/block-mathjax/compile/'.$block->tag;

		$cmd = "$phantomjs ".__DIR__."/../templates/phantomjs/latex2html.js --display $url";
		exec($cmd, $output);
		$content = join("\n", $output);

		$content = preg_replace('/<script\s+type="math\/tex"[^>]+>.*?<\/script>/s', '', $content);

		$model = new Model($this->config, $this->database);
		$block_compiled = $model->getModel('\\modules\\block_mathjax\\classes\\models\\BlockMathJax');

		$compiled = $block_compiled->get(['block_id' => $block->id]);

		if ($compiled) {
			$compiled->content = $content;
			$compiled->update();
		}
		else {
			$block_compiled->block_id = $block->id;
			$block_compiled->content  = $content;
			$block_compiled->insert();
		}
	}

	public function block_render(Block $block) {
		$model  = new Model($this->config, $this->database);
		$content = $model->getModel('\modules\block_mathjax\classes\models\BlockMathJax')->get(['block_id' => $block->id]);
		if ($content) {
			return $content->content;
		}
		return NULL;
	}
}
