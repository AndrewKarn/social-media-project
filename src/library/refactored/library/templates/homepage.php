<?php
    use Shared\Constants;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoe's Social Media Project</title>
    <link href="<?=Constants::WEB_ROOT?>homepage.css" rel="stylesheet" type="text/css">
    <link href="<?=Constants::WEB_ROOT?>login-header.css" rel="stylesheet" type="text/css">
</head>
<body>
<?=$this->getHeader();?>
<main>
    <div id="js-content" class="content">
        <div class="box1">
            <h1>Welcome to Zoe's Social Media Project!</h1>
            <p>I created this project in an effort to really <span class="em">stretch </span>my developer skills.</p>
            <p>This project is powered by <span class="em">PHP</span>.</p>
        </div>
        <div class="box2">
            <div class="form">
                <form id="js-login-form">
                    <span>Existing Users</span>
                    <div>
                        <input name="email" type="email" placeholder="user@example.com" required>
                        <input name="password" type="password" placeholder="password" required>
                        <button id="js-login-btn" class="btn btn-right" type="submit">Login</button>
                    </div>
                </form>
            </div>
            <div class="register">
                <span>New Users</span>
                <button id="js-register-btn" class="btn btn-right" onclick="location.href='<?=Constants::WEB_ROOT?>user/registration'">Register</button>
            </div>
        </div>
    </div>
</main>
<?=$this->getSharedScripts();?>
<script src="<?=Constants::WEB_ROOT?>login.js" type="application/x-javascript"></script>
</body>
</html>