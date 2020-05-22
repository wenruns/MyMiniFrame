<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/14
 * Time: 16:34
 */
try {
    $data = [
        'url' => 'http://www.baidu.com',
        'objectName' => 'test_loan/image',
        'bucket' => 'newloan'
    ];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
    curl_setopt($curl, CURLOPT_URL, 'http://phptools.8kqw.com/html2img.php');
//    curl_setopt($curl, CURLOPT_URL, 'http://172.16.0.227/html2img.php');

    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    $res = curl_exec($curl);
    curl_close($curl);
    var_dump($res);
} catch (\Exception $e) {
    var_dump($e);
}
die;