<?php
use Shared\Constants;
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 5/19/18
 * Time: 2:04 PM
 */
?>
<header>
    <ul id="js-nav-bar">
        <li><a id="js-nav-home" href="<?=Constants::WEB_ROOT?>home/default">Home</a></li>
        <li><a id="js-nav-login" href="#">Login</a></li>
        <li><a id="js-nav-register" href="<?=Constants::WEB_ROOT?>user/registration">Register</a></li>
    </ul>
    <script src="<?=Constants::WEB_ROOT?>nav-bar.js" type="application/x-javascript"></script>
</header>