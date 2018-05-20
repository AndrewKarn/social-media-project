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
    <h1><a href="<?=Constants::WEB_ROOT?>home/default">Z.S.M.P</a></h1>
    <h2>Welcome!</h2>
    <form id="js-login-form">
        <span>Existing Users:</span>
        <div>
            <input name="email" type="email" placeholder="user@example.com" required>
            <input name="password" type="password" placeholder="password" required>
            <button type="submit">Login</button>
        </div>
    </form>
</header>