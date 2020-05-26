<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/6
 * Time: 15:49
 */

namespace wenruns\apple\ipa\install;


class MobileConfig
{
    /**
     * 配置文件保存路径
     * @var string
     */
    protected $_mobile_config_path = '';

    /**
     * 签名文件保存路径
     * @var string
     */
    protected $_sign_mobile_config_path = '';

    /**
     * 证书文件保存路径
     * @var string
     */
    protected $_sign_path = '';

    /**
     * 获取udid回调地址
     * @var string
     */
    protected $_redirect_url = '';

    /**
     * 组织，一般以域名为组织
     * @var string
     */
    protected $_organization = '';

    /**
     * app名称，下载是显示
     * @var string
     */
    protected $_app_name = '';


    /**
     * 子目录
     * @var string
     */
    protected $_sub_dir = '';

    /**
     * 随机码udid
     * @var string
     */
    protected $_random_code = '8C7AD0B8-3900-44DF-A52F-3C4F92921807';


    public function __construct()
    {
        $root_path = substr(__DIR__, 0, strrpos(__DIR__, DIRECTORY_SEPARATOR));
        $this->signMobileConfigPath($root_path . '/resources/mobileconfig/signed/')
            ->mobileConfigPath($root_path . '/resources/mobileconfig/unsigned/')
            ->signPath($root_path . '/resources/sign/');
    }

    /**
     * 设置签名配置保存路径
     * @param $path
     * @return $this
     */
    public function signMobileConfigPath($path)
    {
        $this->_sign_mobile_config_path = rtrim(str_replace('\\', '/', $path), '/') . '/';
        return $this;
    }

    /**
     * 设置配生成置保存路径
     * @param $path
     * @return $this
     */
    public function mobileConfigPath($path)
    {
        $this->_mobile_config_path = rtrim(str_replace('\\', '/', $path), '/') . '/';
        return $this;
    }

    /**
     * 设置签名证书路径
     * @param $path
     * @return $this
     */
    public function signPath($path)
    {
        $this->_sign_path = rtrim(str_replace('\\', '/', $path), '/') . '/';
        return $this;
    }

    /**
     * 设置子目录名称
     * @param $dir
     * @return $this
     */
    public function subDir($dir)
    {
        $this->_sub_dir = trim(str_replace('\\', '/', $dir), '/') . '/';
        return $this;
    }

    /**
     * 设置udid回调地址
     * @param $redirectUri
     * @return $this
     */
    public function redirectUrl($redirectUri)
    {
        $this->_redirect_url = rtrim(str_replace('\\', '/', $redirectUri), '/') . '/';
        return $this;
    }

    /**
     * 设置签名公司（组织）
     * @param $organization
     * @return $this
     */
    public function organization($organization)
    {
        $this->_organization = $organization;
        return $this;
    }

    /**
     * 设置应用名称
     * @param $appNmae
     * @return $this
     */
    public function appName($appNmae)
    {
        $this->_app_name = $appNmae;
        return $this;
    }

    /**
     * 设置playloadUDID随机码
     * @param $radomCode
     * @return $this
     */
    public function radomCode($radomCode)
    {
        $this->_random_code = $radomCode;
        return $this;
    }

    /**
     * 生成配置文件
     * @return array
     */
    public function create()
    {
        $redirectUrl = $this->_redirect_url;
        $organization = $this->_organization;
        $appName = $this->_app_name;
        if (empty($redirectUrl) || empty($organization) || empty($appName)) {
            return [
                'status' => false,
                'msg' => 'redirectUrl或organization或appName为空！'
            ];
        }
        $subDir = $this->_sub_dir;
        $radomCode = $this->_random_code;
        $xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
    <dict>
        <key>PayloadContent</key>
        <dict>
            <key>URL</key>
            <string>$redirectUrl</string>
            <key>DeviceAttributes</key>
            <array>
                <string>UDID</string>
                <string>IMEI</string>
                <string>ICCID</string>
                <string>VERSION</string>
                <string>PRODUCT</string>
                <string>DEVICE_NAME</string>
            </array>
        </dict>
        <key>PayloadOrganization</key>
        <string>$organization</string>
        <key>PayloadDisplayName</key>
        <string>$appName</string>
        <key>PayloadVersion</key>
        <integer>1</integer>
        <key>PayloadUUID</key>
        <string>$radomCode</string>
        <key>PayloadIdentifier</key>
        <string>com.yun-bangshou.profile-service</string>
        <key>PayloadDescription</key>
        <string>该配置文件将帮助用户获取当前iOS设备的UDID号码。This temporary profile will be used to find and display your current device's UDID.</string>
        <key>PayloadType</key>
        <string>Profile Service</string>
    </dict>
</plist>
XML;
        $mobileConfigNmae = md5($redirectUrl . $organization . $appName . $radomCode) . '.mobileconfig';
        $path = $this->_mobile_config_path . (empty($subDir) ? '' : $subDir);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
        }
        file_put_contents($path . $mobileConfigNmae, $xml);
        return [
            'status' => true,
            'mobileconfig' => $mobileConfigNmae,
            'path' => $path
        ];
    }

    /**
     * 配置文件签名
     * @param $mobileConfig
     * @param $key
     * @param $pem
     * @param $cert
     */
    public function sign($mobileConfig)
    {
        $subDir = $this->_sub_dir;
        $signMobileConfigPath = $this->_sign_mobile_config_path . (empty($subDir) ? '' : $subDir);
        $mobileConfigPath = $this->_mobile_config_path . (empty($subDir) ? '' : $subDir);
        if (!is_dir($signMobileConfigPath)) {
            mkdir($signMobileConfigPath, 0777, true);
            chmod($signMobileConfigPath, 0777);
        }
        $mobileConfigFile = $mobileConfigPath . $mobileConfig;
        $signMobileConfigFile = $signMobileConfigPath . $mobileConfig;

        $signPath = $this->_sign_path;

        $key = $signPath . 'ssl/bundle.key';
        $pem = $signPath . 'ssl/bundle.pem';
        $cert = $signPath . 'ssl/bundle.crt';

        $command = 'openssl smime -sign -in ' . $mobileConfigFile . '  -out ' . $signMobileConfigFile . ' -signer ' . $cert . ' -inkey ' . $key . ' -certfile ' . $pem . ' -outform der -nodetach 2>&1';
        exec($command, $out, $status);

        return [
            'output' => $out,
            'status' => $status,
            'path' => $signMobileConfigPath
        ];
    }

    /**
     * 上传证书文件
     * @param $key
     * @param $pem
     * @param $cert
     * @return array
     */
    public function uploadSign($key, $pem, $cert)
    {
        if (is_file($key)) {
            $key = file_get_contents($key);
        }
        if (is_file($pem)) {
            $pem = file_get_contents($pem);
        }
        if (is_file($cert)) {
            $cert = file_get_contents($cert);
        }

        $subDir = md5($key . $pem . $cert);

        $path = $this->_sign_path . (empty($this->_sub_dir) ? '' : $this->_sub_dir);

        if (!is_dir($path . $subDir)) {
            mkdir($path . $subDir, 0777, true);
            chmod($path . $subDir, 0777);
        }
        $fileName = $this->getRandomStr(16);

        file_put_contents($path . $subDir . '/' . $fileName . '.key', $key);
        file_put_contents($path . $subDir . '/' . $fileName . '.pem', $pem);
        file_put_contents($path . $subDir . '/' . $fileName . '.crt', $cert);
        return [
            'key' => $subDir . '/' . $fileName . '.key',
            'pem' => $subDir . '/' . $fileName . '.pem',
            'cert' => $subDir . '/' . $fileName . '.crt',
            'path' => $path
        ];
    }

    /**
     * 获取随机字符串
     * @param int $len
     * @return bool|string
     */
    public function getRandomStr($len = 32)
    {
        $arr = array_merge(range('a', 'z'), range('A', 'Z'), range(0, 9));
        $arr[] = '@';
        $arr[] = '$';
        $arr[] = '#';
        $arr[] = '&';
        shuffle($arr);
        $string = implode('', $arr);
        $radomStr = substr($string, mt_rand(0, strlen($string) - $len), $len);
        return $radomStr;
    }

    public function download($mobileConfig, $signed = true)
    {
        if ($signed) {
            $path = $this->_sign_mobile_config_path;
        } else {
            $path = $this->_mobile_config_path;
        }
        $path .= (empty($this->_sub_dir) ? '' : $this->_sub_dir);

        if (file_exists($path . $mobileConfig)) {
            $file = fopen($path . $mobileConfig, 'rb');
            header('Content-Type: application/octet-stream');
            header('Accept-Ranges: bytes');
            header('Accept-Length: ' . filesize($path . $mobileConfig));
            header('Content-Disposition: attachment; filename=' . $mobileConfig);
            echo fread($file, filesize($path . $mobileConfig));
            fclose($file);
        } else {
            header('HTTP/1.1 404 NOT FOUND');
            echo <<<HTML
<style>
    *{
        padding: 0px;
        margin: 0px;
    }            
    .content{
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        box-sizing: border-box;
        font-size: 2em;
        color: #ccc;
    }
</style>
<div class="content">404 NOT FOUND</div>
HTML;
        }
        exit();
    }


    /**
     * @param $name
     * @param $arguments
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        $name = preg_replace('/([A-Z]{1})/', '_${1}', $name);
        $name = substr(strtolower($name), 3);
        if (isset($this->$name)) {
            return $this->$name;
        }
        return null;
    }

}