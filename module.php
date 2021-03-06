<?php
$_MODULE = [
	"name" => "Block MathJax",
	"description" => "Compile LaTeX in blocks to MathJax HTML/CSS",
	"namespace" => "\\modules\\block_mathjax",
	"config_controller" => "administrator\\BlockMathJax",
	"controllers" => [
		"administrator\\BlockMathJax"
	],
	"hooks" => [
		"models" => [
			"block_insert" => "classes\\Hooks",
			"block_update" => "classes\\Hooks",
			"block_render" => "classes\\Hooks",
			"block_delete" => "classes\\Hooks"
		]
	],
	"default_config" => [
		"phantomjs" => "/path/to/phantomjs"
	]
];
