<?php
include 'header.php';
include 'menu.php';
include 'MetasSeries.php';

$stat = Typecho_Widget::widget('Widget_Stat');
$posts = Typecho_Widget::widget('Widget_Contents_Post_Admin');
$isAllPosts = ('on' == $request->get('__typecho_all_posts') || 'on' == Typecho_Cookie::get('__typecho_all_posts'));
Typecho_Widget::widget('MetasSeries')->to($series);
$series_posts = Typecho_Widget::widget('MetasSeries')->midSeriesPosts();
$current_series = Typecho_Widget::widget('MetasSeries')->midSeries();
?>
<div class="main">
    <div class="body container">
        <?php include 'page-title.php'; ?>
        <div class="row typecho-page-main manage-metas">
            <div class="col-mb-12 col-tb-4" role="main">
                <h2><span><?php _e($current_series[0]['name']); ?> 专题中包含的文章:</span></h2>
                <ul>
                    <?php if ($series_posts): ?>
                        <?php foreach ($series_posts as &$item): ?>
                            <li><?php _e($item['title']) ?>
                                <a href="#" class="remove_post_mid" data-mid="<?php _e($current_series[0]['mid']);?>" data-cid="<?php _e($item['cid']);?>">
                                    <i class="i-delete"></i>
                                </a></li>
                        <?php endforeach;?>
                    <?php endif;?>
                </ul>

            </div>
            <div class="col-mb-12 col-tb-8">
                <form method="post">
                    <div class="typecho-list-operate clearfix">
                        <div class="operate">
                            <label><i class="sr-only"><?php _e('全选'); ?></i><input type="checkbox" class="typecho-table-select-all" /></label>
                            <div class="btn-group btn-drop">
                                <button class="btn dropdown-toggle btn-s" type="button"><i class="sr-only"><?php _e('操作'); ?></i><?php _e('选中项'); ?> <i class="i-caret-down"></i></button>
                                <ul class="dropdown-menu">
                                    <li class="multiline">
                                        <button type="button" class="btn merge btn-s" rel="<?php $security->index('/action/post_series/?do=merge'); ?>"><?php _e('合并到'); ?></button>
                                        <select name="merge">
                                            <?php $series->parse('<option value="{mid}">{name}</option>'); ?>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="search" role="search">
                            <?php if ('' != $request->keywords || '' != $request->category): ?>
                                <a href="<?php $options->adminUrl('manage-posts.php'
                                    . (isset($request->status) || isset($request->uid) ? '?' .
                                        (isset($request->status) ? 'status=' . htmlspecialchars($request->get('status')) : '') .
                                        (isset($request->uid) ? '?uid=' . htmlspecialchars($request->get('uid')) : '') : '')); ?>"><?php _e('&laquo; 取消筛选'); ?></a>
                            <?php endif; ?>
                            <input type="text" class="text-s" placeholder="<?php _e('请输入关键字'); ?>" value="<?php echo htmlspecialchars($request->keywords); ?>" name="keywords" />
                            <select name="category">
                                <option value=""><?php _e('所有分类'); ?></option>
                                <?php Typecho_Widget::widget('Widget_Metas_Category_List')->to($category); ?>
                                <?php while($category->next()): ?>
                                    <option value="<?php $category->mid(); ?>"<?php if($request->get('category') == $category->mid): ?> selected="true"<?php endif; ?>><?php $category->name(); ?></option>
                                <?php endwhile; ?>
                            </select>
                            <button type="submit" class="btn btn-s"><?php _e('筛选'); ?></button>
                            <?php if(isset($request->uid)): ?>
                                <input type="hidden" value="<?php echo htmlspecialchars($request->get('uid')); ?>" name="uid" />
                            <?php endif; ?>
                            <?php if(isset($request->status)): ?>
                                <input type="hidden" value="<?php echo htmlspecialchars($request->get('status')); ?>" name="status" />
                            <?php endif; ?>
                        </div>
                    </div><!-- end .typecho-list-operate -->

                    <div class="typecho-table-wrap">
                        <table class="typecho-list-table">
                            <colgroup>
                                <col width="20"/>
                                <col width="6%"/>
                                <col width="35%"/>
                                <col width=""/>
                            </colgroup>
                            <thead>
                            <tr>
                                <th> </th>
                                <th> </th>
                                <th><?php _e('标题'); ?></th>
                                <th><?php _e('作者'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($posts->have()): ?>
                                <?php while($posts->next()): ?>
                                    <tr id="<?php $posts->theId(); ?>">
                                        <td><input type="checkbox" value="<?php $posts->cid(); ?>" name="cid[]"/></td>
                                        <td><a href="<?php $options->adminUrl('manage-comments.php?cid=' . ($posts->parentId ? $posts->parentId : $posts->cid)); ?>" class="balloon-button size-<?php echo Typecho_Common::splitByCount($posts->commentsNum, 1, 10, 20, 50, 100); ?>" title="<?php $posts->commentsNum(); ?> <?php _e('评论'); ?>"><?php $posts->commentsNum(); ?></a></td>
                                        <td>
                                            <a href="<?php $options->adminUrl('write-post.php?cid=' . $posts->cid); ?>"><?php $posts->title(); ?></a>
                                            <?php
                                            if ($posts->hasSaved || 'post_draft' == $posts->type) {
                                                echo '<em class="status">' . _t('草稿') . '</em>';
                                            } else if ('hidden' == $posts->status) {
                                                echo '<em class="status">' . _t('隐藏') . '</em>';
                                            } else if ('waiting' == $posts->status) {
                                                echo '<em class="status">' . _t('待审核') . '</em>';
                                            } else if ('private' == $posts->status) {
                                                echo '<em class="status">' . _t('私密') . '</em>';
                                            } else if ($posts->password) {
                                                echo '<em class="status">' . _t('密码保护') . '</em>';
                                            }
                                            ?>
                                            <a href="<?php $options->adminUrl('write-post.php?cid=' . $posts->cid); ?>" title="<?php _e('编辑 %s', htmlspecialchars($posts->title)); ?>"><i class="i-edit"></i></a>
                                            <?php if ('post_draft' != $posts->type): ?>
                                                <a href="<?php $posts->permalink(); ?>" title="<?php _e('浏览 %s', htmlspecialchars($posts->title)); ?>"><i class="i-exlink"></i></a>
                                            <?php endif; ?>
                                        </td>
                                        <td><a href="<?php $options->adminUrl('manage-posts.php?uid=' . $posts->author->uid); ?>"><?php $posts->author(); ?></a></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6"><h6 class="typecho-list-table-title"><?php _e('没有任何文章'); ?></h6></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
?>
<script type="text/javascript">

    $('.typecho-list-table').tableSelectable({
        checkEl     :   'input[type=checkbox]',
        rowEl       :   'tr',
        selectAllEl :   '.typecho-table-select-all',
        actionEl    :   '.dropdown-menu a'
    });

    $('.btn-drop').dropdownMenu({
        btnEl       :   '.dropdown-toggle',
        menuEl      :   '.dropdown-menu'
    });

    $('.dropdown-menu button.merge').click(function () {
        var btn = $(this);
        btn.parents('form').attr('action', btn.attr('rel')).submit();
    });

    $('.remove_post_mid').on('click', function(e) {
        var obj = $(this);
        var cid = obj.data("cid");
        var mid = obj.data("mid");
        $.post("<?php $security->index('/action/post_series/?do=remove_post_mid'); ?>", $.param({mid : mid, cid: cid}), function() {
            obj.parents('li').remove();
        });

    });

</script>
<?php include 'footer.php'; ?>
