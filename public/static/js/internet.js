import { common } from '@/static/js/common.js';

function InternetObject(){
	
	this.configs = common.getStorage('custom_config')
	
	function _init(){
		
	}
	_init();
	
	// 请求类型
	this.type = 'http';
	// 回调方法
	this.callbacks = {};
	
	
	// 通过websocket发送数据
	this.sendByTcp = ()=>{
		
	}
	
	// 通过http发送数据
	this.sendByHttp = ({
		data = null,
		flag = null,
		callback = null,
	})=>{
		var api = this.configs.apis[flag];
		if(api && api.url){
			uni.request({
				url: api.url,
				method:api.method?api.method:'GET',
				data:data,
				dataType: api.dataType?api.dataType:'json',
				header: api.header?api.header:{},
				timeout: api.timeout ? api.timeout : 30000,
				responseType: api.responseType ? api.responseType : 'text',
				sslVerify: api.sslVerify ? api.sslVerify : true,
				withCredentials: api.withCredentials ? api.withCredentials : false,
				success: (rst)=>{
					callback && callback(rst);
				},
				fail: (err)=> {
					callback && callback(err);
				}
			})
		}
	}
	
	
	this.sendData = ({
		data=null,
		flag=null,
		callback=null,
	})=>{
		if(!flag){
			return {
				status: false,
				error: "请输入flag参数",
			};
		}
		
		if(this.configs.connectType == 'http'){
			this.sendByHttp({
				data,
				flag,
				callback,
			})
		}else{
			this.sendByTcp({
				data,
				flag,
				callback,
			});
		}
	}
	
}

const internet = new InternetObject();


/**
 * 网络连接模块
 */
module.exports = {
	internet,
}