/**
 * html截图生成图片
 *
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/8
 * Time: 14:13
 */
try{
	var system = require('system');
	// console.log(system.args);
	if (system.args.length == 1) {
		throw '请输入参数-url=URL -path=SAVEPATH';
	}
	
	var args = new Array();
	system.args.forEach(function(item, index){
		// console.log(item, index);
		if (index > 0) {
			item = item.split('=');
			// console.log(item);
			args[item[0]] = item[1];
		}
	});

	var settings = args['settings'];
	settings = settings.replace(/'/g, '"');
	settings = JSON.parse(settings);
	var page = require('webpage').create();
	var address = args['url']; //填写需要打印的文件位置
	var output = args['path']; //存储文件路径和名称
	// page.viewportSize = { width: 600, height: 200 };//设置长宽
	page.open(address, settings, function (status) {
		if (status !== 'success') {
			console.log('Unable to load the address!');
			phantom.exit();
		} else {
			console.log(123);
			//window.setTimeout(function () {
				console.log(page.render(output));
				phantom.exit();
			//}, 500);
		}
	});
}catch(e){
	console.log(e);
	phantom.exit();
}
