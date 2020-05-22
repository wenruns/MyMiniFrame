<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2019/10/31
 * Time: 11:18
 */

namespace app\controller;


class Controller
{
    public function index($id)
    {
        return 'index->' . $id;
    }

    public function show($id)
    {
        return $this->view('show');
    }

    public function view($template = '', $options = [])
    {
        return view($template, $options);
    }

}