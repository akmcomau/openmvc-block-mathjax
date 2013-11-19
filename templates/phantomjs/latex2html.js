var page = require('webpage').create();
var system = require('system');

//
//  Get arguments, and print usage if not enough
//
if (system.args.length === 1) {
	console.log('Usage: '+system.args[0]+' [--display] url');
	phantom.exit();
}
var display = false, url = system.args[1];
if (url === "--display") {display = true; url = system.args[2]}

//
//  Function to allow passing arguments to page.evaluate()
//
function evaluate(page, func) {
	var args = [].slice.call(arguments, 2);
	var fn = "function() {return ("+func.toString()+").apply(this,"+JSON.stringify(args)+")}";
	return page.evaluate(fn);
}

//Open a page
page.open(url, function (status) {
	if (status !== "success") {
		console.log("Unable to access network");
	}
	else {
		page.onAlert = function (msg) {
			if (msg === "MathJax Done") {
				var p = page.evaluate(function () {
					return document.getElementsByTagName('body')[0].innerHTML
				});
				p = p.replace(/><([^/])/g,">\n<$1")
					.replace(/(<\/[a-z]*>)(?=<\/)/g,"$1\n")
					.replace(/<svg /,'<svg xmlns="http://www.w3.org/2000/svg" ')
					.replace(/<use ([^>]*)href/g,'<use $1xlink:href');
				console.log(p);
				phantom.exit();
			}
			else if (msg === "MathJax Timeout") {
				console.log("Timed out waiting for MathJax");
				phantom.exit();
			}
		};

		page.evaluate(function () {
			setTimeout(function () {alert("MathJax Timeout")},30000);
		});
	}
});
