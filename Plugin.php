<?php
/**
 * 首页过滤指定分类
 * 
 * @package CateFilter
 * @author Rakiy,WoodChen,ymz316
 * @version 1.2.5
 * @link http://woodchen.ink
 */
class CateFilter_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->indexHandle = array('CateFilter_Plugin', 'filter'); 
        return _t('插件已激活，现在可以对插件进行设置！');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}

    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){

        // $CateId = new Typecho_Widget_Helper_Form_Element_Text('CateId', NULL, '0', _t('首页不显示的分类'), _t('多个请用英文逗号隔开'));
        // $form->addInput($CateId);

        Typecho_Widget::widget('Widget_Metas_Category_List')->to($categories);
        while($categories->next()){$cate[$categories->mid]=$categories->name;}//获取分类列表
        
        $CateId = new Typecho_Widget_Helper_Form_Element_Checkbox('CateId', $cate,[], _t('勾选首页不想显示的分类'), NULL);
        $form->addInput($CateId->multiMode());
    }

    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
 

    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function filter($obj, $select){
        // if('/feed' == strtolower(Typecho_Request::getInstance()->getPathinfo()) || '/feed/' == strtolower(Typecho_Request::getInstance()->getPathinfo())) return $select;

        $CateIds = Typecho_Widget::widget('Widget_Options')->plugin('CateFilter')->CateId;
        if(!$CateIds) return $select;       //没有写入值，则直接返回
        $select = $select->join('table.relationships','table.relationships.cid = table.contents.cid','right')->group('table.contents.cid');
        // $CateIds = explode(',', $CateIds);
        // $CateIds = array_unique($CateIds);  //去除重复值
        foreach ($CateIds as $k => $v) {
            $select = $select->where('table.relationships.mid != '.intval($v));//确保每个值都是数字；排除重复文章
        } 
        return $select;
    }   
 
}
