<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
include 'MetasSeries.php';
class PostSeries_Action extends Typecho_Widget implements Widget_Interface_Do {

    function action()
    {
        // TODO: Implement action() method.
        //$this->on($this->request->is('do=clearAll'))->clearAll();
        //$this->on($this->request->is('do=deleteLog'))->deleteLog();
        $this->on($this->request->is('do=insert'))->insert();
        $this->on($this->request->is('do=update'))->update();
        $this->on($this->request->is('do=sort'))->sort();
        $this->on($this->request->is('do=merge'))->mergeSeries();
        $this->on($this->request->is('do=remove_post_mid'))->remove_post_mid();

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'javascript:history.back(-1);';
        $this->response->redirect($referer);
    }

    public function series() {
        include 'series.php';
    }

    public function insert() {
        Typecho_Widget::widget('MetasSeries')->insertSeries();
    }

    public function update() {
        Typecho_Widget::widget('MetasSeries')->updateSeries();
    }

    public function sort() {
        Typecho_Widget::widget('MetasSeries')->sortSeries();
    }

    public function mergeSeries() {
        Typecho_Widget::widget('MetasSeries')->mergeSeries();
    }

    public function remove_post_mid() {
        $mid = $this->request->filter('int')->mid;
        $cid = $this->request->filter('int')->cid;
        Typecho_Widget::widget('MetasSeries')->removeSeriesCid($mid, $cid);
    }




}



