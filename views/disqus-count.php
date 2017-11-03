<?php
/**
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2013 - 2017
 * @package yii2-social
 * @version 1.3.4
 */
use yii\helpers\Html;

/**
 * Comment count view template for yii\social\Disqus widget
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
?>
    <!--[if (gt IE 8)]><!-->
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        <?= $variables ?>
        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function () {
            var s = document.createElement('script');
            s.async = true;
            s.type = 'text/javascript';
			s.src = 'https://<?= $shortname ?>.disqus.com/count.js';
            (document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
        }());
    </script>
    <!--<![endif]-->
<?= $noscript ?>