<?php
/**
 * FluitoPHP(tm): Lightweight MVC (http://www.fluitophp.org)
 * Copyright (c) 2017, FluitoSoft (http://www.fluitosoft.com)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2017, FluitoSoft (http://www.fluitosoft.com)
 * @link          http://www.fluitophp.org FluitoPHP(tm): Lightweight MVC
 * @since         0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?><div class="mt-4 mb-2">
    <h3 class="alert alert-danger" style="overflow: auto;" role="alert"><?php echo $this->Get('excpMsg'); ?></h3>
</div>

<?php if (DEBUG): ?>
    <div class="alert alert-danger" role="alert">
        Stack Trace:
        <?php
        var_dump($this->
                        Get('excpTrc'));
        ?>
    </div>
<?php endif; ?>