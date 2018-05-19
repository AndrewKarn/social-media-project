<?php
    use Shared\Constants;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Zoe's Social Media Project</title>
    <link href="<?=Constants::WEB_ROOT?>main.css" rel="stylesheet" type="text/css">
    <script src="<?=Constants::WEB_ROOT?>ZRequest.js" type="application/x-javascript"></script>
</head>
<body>
<main>
    <div class="box1">
        <span>Existing Users Login:</span>
        <form id="js-login-form">
            <div>
                <label for="email">Input your email:</label>
                <input name="email" type="email" >
            </div>
            <div>
                <label for="password">Input your password:</label>
                <input name="password" type="password">
            </div>
            <div>
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
    <br />
    <div class="box2">
        <form action="http://www.zoes-social-media-project.com/user/register/" method="post" id="js-register-form">
            <div>
                <label for="firstname">First Name:</label>
                <input id="firstname-register" type="text" name="firstname" required>
            </div>
            <div>
                <label for="lastname">Last Name:</label>
                <input id="lastname-register" type="text" name="lastname" required>
            </div>
            <div>
                <label for="dob">Birth Date:</label>
                <input id="dob-register" type="Date" name="dob" required>
            </div>
            <div>
                <label for="email">Email (This will be used for login):</label>
                <input id="email-register" type="email" name="email" required>
            </div>
            <div>
                <label for="password">Enter Password:</label>
                <input id="password-register1" type="password" name="password" required>
            </div>
            <div>
                <label for="passwordVerify">Re-enter Password:</label>
                <input id="password-register2" type="password" name="passwordVerify" required>
            </div>
            <div>
                <button id="reset-registration" type="reset">Reset</button>
                <button id="submit-registration" type="submit">Submit</button>
            </div>
        </form>
    </div>
</main>
<script src="<?=Constants::WEB_ROOT?>ZUtils.js" type="application/x-javascript"></script>
<script src="<?=Constants::WEB_ROOT?>homepagehandler.js" type="application/x-javascript"></script>
</body>
</html>