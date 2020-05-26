<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/5/26
 * Time: 14:50
 */

namespace wenruns\apple\ipa\install\driv;


abstract class abstractDriv
{
    abstract public function init($options);

    
    abstract public function handle();
    
    
}