<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013 - 2017
 * @package yii2-social
 * @version 1.3.4
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
			var d = document, s = d.createElement('script');
			s.src = 'https://<?= $shortname ?>.disqus.com/embed.js';
			s.setAttribute('data-timestamp', +new Date());
			(d.head || d.body).appendChild(s);
        })();
    </script>
    <!--<![endif]-->
    
    <!--[if (lt IE 9)]>
        <div class="alert alert-warning"><?= Yii::t('kvsocial', '<strong>Note:</strong> Please use an updated browser to view comments.') ?></div>
    <![endif]-->
<?= $noscript ?>
<?= $credits ?>