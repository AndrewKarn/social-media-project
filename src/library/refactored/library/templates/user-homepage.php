<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 6/15/18
 * Time: 2:38 PM
 */

use Shared\Constants;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$this->getTitle(); ?></title>
    <?=$this->getStyles(); ?>
</head>
<body>
<?=$this->getHeader(); ?>
<main>
    <div id="js-content" class="content">
        <div class="box1">
            <? /* Content here */ ?>
        </div>
    </div>
</main>
<?= $this->getSharedScripts(); ?>
<?= $this->getUniqueScripts(); ?>
</body>
</html>
