import { common } from '@/static/js/common.js';
import { internet } from '@/static/js/internet.js';

function UserObject(){
	/**
	 * 检测是否拥有权限
	 * @param {Object} authFlag
	 */
	this.checkAuth = (authFlag)=>{
		var auth = this.getUserInfo('auth');
		if(auth){
			if(auth.indexOf(authFlag) >= 0){
				return true;
			}
		}
		return false;
	}
	
	
	/**
	 * 检测是否已登录
	 */
	this.checkLogin = ()=>{
		if(this.getUserInfo()){
			return true;
		}
		return false;
	}
	
	/**
	 * 获取用户信息
	 */
	this.getUserInfo =(index='')=>{
		var userInfo = common.getStorage('user_info', null);
		if(index && userInfo){
			return userInfo[index];
		}
		return userInfo;
	}
	
	this.login = (data)=>{
		internet.sendData({
			data: data,
			flag: "login",
			callback: function(rst){
				console.log(rst);
			}
		})
	}
	
}

const user = new UserObject();

/**
 * 用户管理模块
 */
module.exports = {
	user,
}