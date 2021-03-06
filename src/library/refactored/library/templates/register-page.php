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
<?=$this->getHeader();?>
<main>
    <div id="js-content" class="content">
        <div class="box1">
            <table class="form" id="js-registration-table">
                <caption>New Users: Register!</caption>
                <form action="http://www.zoes-social-media-project.com/user/registration" method="post" id="js-register-form">
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
                            <button class="btn btn-reset" id="reset-registration" type="reset">Reset</button>
                            <button class="btn btn-right" id="submit-registration" type="submit">Submit</button>
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </div>
</main>
<?=$this->getSharedScripts(); ?>
<?=$this->getUniqueScripts(); ?>
</body>
</html>
