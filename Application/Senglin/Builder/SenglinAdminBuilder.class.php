<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-12
 * Time: AM10:08
 */

namespace Senglin\Builder;
use Think\Controller;


class SenglinAdminBuilder extends Controller {
     public function display($templateFile='',$charset='',$contentType='',$content='',$prefix='') {
        //获取模版的名称
        $template = dirname(__FILE__) . '/../View/default/Builder/' . $templateFile . '.html';

        //显示页面
        parent::display($template);
    }

    protected function compileHtmlAttr($attr) {
        $result = array();
        foreach($attr as $key=>$value) {
            $value = htmlspecialchars($value);
            $result[] = "$key=\"$value\"";
        }
        $result = implode(' ', $result);
        return $result;
    }
}

