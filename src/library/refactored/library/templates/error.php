<?php
    use Shared\Constants;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$this->getTitle();?></title>
    <?=$this->getStyles(); ?>
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