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
        <h1><?=$this->getMessage();?></h1>
        <h2><?="Trace:\n"?></h2>
        <pre><?=$this->getStackTrace();?></pre>
        <p><?='at: ' . date('g:i:s');?></p>
    </main>
    <?=$this->getSharedScripts();?>
</body>
</html>