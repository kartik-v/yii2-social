<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013 - 2015
 * @package yii2-social
 * @version 1.3.1
 */
use yii\helpers\Html;

/**
 * Comments plugin view template for yii\social\Disqus widget
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
?>
    <div id="disqus_thread"></div>
    <!--[if (gt IE 8)]><!-->
    <script type="text/javascript">
        <?= $variables ?>
        (function () {
            var dsq = document.createElement('script');
            dsq.type = 'text/javascript';
            dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <!--<![endif]-->
    
    <!--[if (lt IE 9)]>
        <div class="alert alert-warning"><?= Yii::t('kvsocial', '<strong>Note:</strong> Please use an updated browser to view comments.') ?></div>
    <![endif]-->
<?= $noscript ?>
<?= $credits ?>