function CommonObject(){
	/**
	 * 获取用户自定义配置
	 * @param {Object} callback
	 */
	this.getCustomConfig = (callback)=>{
		uni.request({
				url:'./static/json/custom.json',
				method: "GET",
				dataType: 'json',
				complete: (rst) => {
					callback && callback(rst);
				}
			});
	}
	
	/**
	 * 获取本地缓存信息
	 */
	this.getStorage = (index = '', defaultValue = null)=>{
		try{
			if(index){
				var value = uni.getStorageSync(index);
				if(!value){
					return defaultValue;
				}
				return value;
			}
			var value = {};
			var info = uni.getStorageInfoSync();
			info.keys.forEach(function(item, index){
				value[item] = uni.getStorageSync(item);
			})
			return value;
		}catch(e){
			console.warn(e);
			return false;
		}
	}
	
	/**
	 * 设置本地缓存信息
	 * @param {Object} index
	 * @param {Object} value
	 */
	this.setStorage = (index, value) => {
		try{
			uni.setStorageSync(index, value);
		}catch(e){
			console.warn(e);
			return false;
		}
		return true;
	}
	
	/**
	 * 保存用户设置
	 * @param {Object} index
	 * @param {Object} value
	 */
	this.setCustomSetting= (index, value) => {
		try{
			console.log(index+'='+value)
			var customSetting = this.getStorage('customer_setting', {});
			customSetting[index] = value;
			this.setStorage('customer_setting', customSetting);
			return true;
		}catch(e){
			console.warn(e)
			return false;
		}
	}
	
	/**
	 * 获取用户设置信息
	 */
	this.getCustomSetting = (index = '', defaultValue = null) => {
		try{
			var customSetting = this.getStorage('customer_setting', {});
			if(index){
				if(customSetting[index]){
					return customSetting[index];
				}
				return defaultValue;
			}
			return customSetting;
		}catch(e){
			console.warn(e)
			return false;
		}
	}
}

const common = new CommonObject();



/**
 * 通用模块
 */
module.exports = {
	common,
}