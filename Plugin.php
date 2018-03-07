<?php
/**
 * 文章专题插件
 *
 * @package PostSeries
 * @author hongweipeng
 * @version 0.0.1
 * @link https://www.hongweipeng.com
 */
class PostSeries_Plugin implements Typecho_Plugin_Interface {
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate() {
        //Typecho_Plugin::factory('Widget_Archive')->header = array(__CLASS__, 'header');
        //Typecho_Plugin::factory('Widget_Archive')->footer = array(__CLASS__, 'footer');


        //Typecho_Plugin::factory('admin/write-post.php')->bottom = array(__CLASS__, 'render');
        Helper::addAction('post_series', 'PostSeries_Action');
        Helper::addPanel(3, 'PostSeries/manage-series.php', '临时面板', '管理专题文章',
            'administrator', false, 'extending.php?panel='.urlencode(trim("PostSeries/series.php", '/')));
        Helper::addPanel(3, 'PostSeries/series.php', '专题', '管理专题',
            'administrator', true);
        //Helper::addRoute('post_series_route', __TYPECHO_ADMIN_DIR__.'post-series/series', 'PostSeries_Action', 'series');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){
        Helper::removePanel(3, 'PostSeries/manage-series.php');
        Helper::removePanel(3, 'PostSeries/series.php');

        //Helper::removeRoute('post_series_route');
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){}

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}


    public static function render() {

    }
}