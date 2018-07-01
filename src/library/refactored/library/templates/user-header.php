<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 7/1/18
 * Time: 4:10 PM
 */
use Shared\Constants;
?>
<header>
    <ul>
        <li id="js-user-home" class="selected" tabindex="1"><a href="<?=Constants::WEB_ROOT?>home" tabindex="-1">Home</a></li>
        <li id="js-user-messages" tabindex="2"><a href="<?=Constants::WEB_ROOT?>messages" tabindex="-1">Messages</a></li>
        <li id="js-user-profile" tabindex="3"><a href="<?=Constants::WEB_ROOT?>profile" tabindex="-1">User</a></li>
        <li id="js-user-settings" tabindex="4"><a href="<?=Constants::WEB_ROOT?>settings" tabindex="-1">Settings</a></li>
        <li id="js-main-home" tabindex="5"><a href="<?=Constants::WEB_ROOT?>home/default" tabindex="-1">Z.S.M.P.</a></li>
    </ul>
    <i id="js-logout-button" class="logout" tabindex="6"></i>
</header>