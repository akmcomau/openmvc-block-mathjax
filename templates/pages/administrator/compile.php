<!DOCTYPE html>
<html lang="en">
<head>
	<script src="<?php echo $this->config->siteConfig()->enable_latex; ?>"></script>
	<script type="text/javascript">
	  MathJax.Hub.Config({
		  tex2jax: {
			  inlineMath: [["$","$"],["\\(","\\)"]]
		  }
	  });
	  MathJax.Hub.Queue([alert,'MathJax Done']);
	</script>
</head>
<body>
	<?php echo $content; ?>
</body>
</html>
