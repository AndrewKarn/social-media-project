<?php
    use Shared\Constants;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Zoe's Social Media Project</title>
    <link href="<?=Constants::WEB_ROOT?>main.css" rel="stylesheet" type="text/css">
    <link href="<?=Constants::WEB_ROOT?>login-header.css" rel="stylesheet" type="text/css">
</head>
<body>
<?=$this->getHeader();?>
<main>
    <div class="box1">

        <table class="form" id="js-registration-table">
            <caption>New Users: Register!</caption>
            <form action="<?=Constants::WEB_ROOT?>user/register" method="post" id="js-register-form">
            <tr>
                <td><label for="firstname">First Name:</label></td>
                <td><input id="firstname-register" type="text" name="firstname" required></td>
            </tr>
            <tr>
                <td><label for="lastname">Last Name:</label></td>
                <td><input id="lastname-register" type="text" name="lastname" required></td>
            </tr>
            <tr>
                <td><label for="email">Email:</label></td>
                <td><input id="email-register" type="email" name="email" required></td>
            </tr>
            <tr>
                <td><label for="dob">Birth Date:</label></td>
                <td><input id="dob-register" type="Date" name="dob" required></td>
            </tr>

            <tr>
                <td><label for="password">Enter Password:</label></td>
                <td><input id="password-register1" type="password" name="password" required></td>
            </tr>
            <tr>
                <td><label for="passwordVerify">Re-enter Password:</label></td>
                <td><input id="password-register2" type="password" name="passwordVerify" required></td>
            </tr>
            <tr class="form-btns">
                <td colspan="2">
                    <button id="reset-registration" type="reset">Reset</button>
                    <button id="submit-registration" type="submit">Submit</button>
                </td>
            </tr>
            </form>
        </table>

    </div>
</main>
<?=$this->getSharedScripts();?>
<script src="<?=Constants::WEB_ROOT?>homepage.js" type="application/x-javascript"></script>
</body>
</html>