<?php
    use Shared\Constants;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?=Constants::WEB_ROOT?>main.css" rel="stylesheet" type="text/css">
    <link href="<?=Constants::WEB_ROOT?>login-header.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="<?=Constants::WEB_ROOT?>std-error.css" type="text/css">
    <title><?=$this->getTitle();?></title>
</head>
<?=$this->getHeader();?>
<body>
    <main>
        <div class="box1">
            <div class="received-errors">
                <?php foreach($this->getMessages() as $message): ?>
                <p><?=$message?></p>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <?=$this->getSharedScripts();?>
</body>
</html>