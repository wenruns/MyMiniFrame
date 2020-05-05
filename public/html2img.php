<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/31
 * Time: 11:08
 */
$autoload = require_once(__DIR__ . '/../core/autoload/Autoload.php');

$tool = new \src\htmlToImage\HtmlToImgService();
$tool->html2Img();
