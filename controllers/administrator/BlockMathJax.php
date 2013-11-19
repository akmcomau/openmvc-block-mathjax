<?php

namespace modules\block_mathjax\controllers\administrator;

use core\classes\renderable\Controller;
use core\classes\Model;
use core\classes\Pagination;
use core\classes\FormValidator;

class BlockMathJax extends Controller {

	protected $show_admin_layout = TRUE;

	protected $permissions = [
		'index' => ['administrator'],
		'config' => ['administrator'],
	];

	public function index() {
		$this->language->loadLanguageFile('administrator/block_mathjax.php', 'modules'.DS.'block_mathjax');
		$form_search = $this->getBlockSearchForm();

		$params = ['site_id' => ['type'=>'in', 'value'=>$this->allowedSiteIDs()]];
		if ($form_search->validate()) {
			$values = $form_search->getSubmittedValues();
			foreach ($values as $name => $value) {
				if (preg_match('/^search_(title|tag)$/', $name, $matches) && $value != '') {
					$params['block_'.$matches[1]] = ['type'=>'like', 'value'=>'%'.$value.'%'];
				}
				elseif ($name == 'search_category' && (int)$value != 0) {
					$params['block_category_id'] = (int)$value;
				}
			}
		}

		// get all the blocks
		$pagination = new Pagination($this->request, 'block_title');
		$model  = new Model($this->config, $this->database);
		$block_category = $model->getModel('\core\classes\models\BlockCategory');
		$block  = $model->getModel('\modules\block_mathjax\classes\models\BlockMathJax');
		$blocks = $block->getMulti($params, ['block_title'=>'asc'], $pagination->getLimitOffset());
		$pagination->setRecordCount($block->getCount($params));

		$data = [
			'form' => $form_search,
			'blocks' => $blocks,
			'pagination' => $pagination,
			'categories' => $block_category->getAsOptions($this->allowedSiteIDs()),
		];

		$template = $this->getTemplate('pages/administrator/list.php', $data, 'modules'.DS.'block_mathjax');
		$this->response->setContent($template->render());
	}

	public function config() {
		$this->language->loadLanguageFile('administrator/skeleton.php', 'core'.DS.'modules'.DS.'skeleton');
		$template = $this->getTemplate('pages/administrator/skeleton.php', [], 'core'.DS.'modules'.DS.'skeleton');
		$this->response->setContent($template->render());
	}

	public function compile($tag) {
		$model = new Model($this->config, $this->database);
		$block = $model->getModel('\core\classes\models\Block')->get([
			'tag' => $tag,
		]);

		$this->layout = NULL;
		$data = [
			'content' => $block->content,
		];

		$template = $this->getTemplate('pages/administrator/compile.php', $data, 'modules'.DS.'block_mathjax');
		$this->response->setContent($template->render());
	}

	protected function getBlockSearchForm() {
		$inputs = [
			'search_title' => [
				'type' => 'string',
				'required' => FALSE,
				'max_length' => 256,
				'message' => $this->language->get('error_search_title'),
			],
			'search_tag' => [
				'type' => 'string',
				'required' => FALSE,
				'max_length' => 64,
				'message' => $this->language->get('error_search_title'),
			],
			'search_category' => [
				'type' => 'integer',
				'required' => FALSE,
			],
		];

		return new FormValidator($this->request, 'form-block-search', $inputs);
	}
}