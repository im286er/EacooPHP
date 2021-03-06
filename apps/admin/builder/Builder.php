<?php
namespace app\admin\builder;
//use app\admin\builder\AdminList;
use app\common\controller\Base;
use think\Exception;

/**
 * Builder：快速建立管理页面。
 *
 * Class Builder
 * @package Admin\Builder
 */
class Builder extends Base {

    /**
     * 开启Builder
     * @param  string $type 构建器名称
     * @return [type]       [description]
     */
    public static function run($type='')
    {
        if ($type == '') {
            throw new Exception('未指定构建器', 100001);
        } else {
            $type = ucfirst(strtolower($type));
        }

        // 构造器类路径
        $class = '\\app\\admin\\builder\\Admin'. $type;

        return new $class;

    }

    /**
     * 模版输出
     * @param  string $template 模板文件名
     * @param  array  $vars         模板输出变量
     * @param  array  $replace      模板替换
     * @param  array  $config       模板参数
     * @return [type]               [description]
     */
    public function fetch($template='',$vars = [], $replace = [], $config = []) {
        if (PUBLIC_RELATIVE_PATH=='') {
            $template_path_str = '../';
        } else{
            $template_path_str = './';
        }
        $this->assign('template_path_str',$template_path_str);
        $this->assign('_builder_style_', $template_path_str.'apps/admin/view/builder/style.html');  // 页面样式
        $this->assign('_builder_javascript_', $template_path_str.'apps/admin/view/builder/javascript.html');  // 页面样式
        //显示页面
        if ($template!='') {
            echo parent::fetch($template,$vars,$replace,$config);
        }
        
    }

    protected function compileHtmlAttr($attr) {
        $result = [];
        foreach($attr as $key=>$value) {
            $value = htmlspecialchars($value);
            $result[] = "$key=\"$value\"";
        }
        $result = implode(' ', $result);
        return $result;
    }
}

