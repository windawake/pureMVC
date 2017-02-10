<?php
/**
 * Created by PhpStorm.
 * User: zhangzibin
 * Date: 2017/2/10
 * Time: 11:09
 */

namespace Controllers;


class Controller
{
    public function view($filename,$data=array())
    {
        $cacheFile = ROOT_PATH.'/views/cache/'.$filename.'.blade.php';
        $content = file_get_contents(ROOT_PATH.'/views/'.$filename.'.blade.php');
        //变量
        $content = preg_replace('/\{\{\s*(.*?)\s*\}\}/i', '<?php echo ${1}; ?>', $content);
        //if语句处理
        $content = preg_replace('/@if\s*\((.*?)\)/i', '<?php if(${1}) { ?>', $content);
        $content = preg_replace('/@else\s*if\s*\((.*?)\)/i', '<?php } elseif(${1}) { ?>', $content);
        $content = preg_replace('/@else/i', '<?php } else { ?>', $content);
        $content = preg_replace('/@endif/i', '<?php } ?>', $content);
        //foreach语句处理
        $content = preg_replace('/@foreach\s*\((.*?)\)/i', '<?php foreach(${1}) { ?>', $content);
        $content = preg_replace('/@endforeach/i', '<?php } ?>', $content);
        file_put_contents($cacheFile,$content);

        ob_start();
        extract($data);
        require_once($cacheFile);
        $output = ob_get_clean();

        return $output;
    }
}