<?php
/**
 * Created by PhpStorm.
 * User: zoeboym
 * Date: 4/7/18
 * Time: 11:38 AM
 */

namespace Home;


class HomeView
{
    public function __construct($version = 'unverified', $options = [])
    {
        switch ($version) {
            case 'unverified':
                error_log('entered unverified page');
                $this->showUnauthenticatedHomepage();
                break;
            case 'emailFwd':
                error_log('entered emailFwd');
                $this->showEmailForwardHomepage($options);
                break;
            case 'authenticated':
                error_log('entered authenticated for ' . $options['email']);
                $this->showAuthenticatedHomepage($options);
                break;
            case 'pswrdFail':
                error_log('password login failed. ' . $options['loginAttempts']);
                $this->showPswrdFail($options);
        }
    }

    /** ---Sends default static homepage to client---
     *
     */
    private function showUnauthenticatedHomepage() {
        error_log("then called showUnauthenticated function");
        $html = file_get_contents('unauthenticated-homepage.html', FILE_USE_INCLUDE_PATH);
        echo $html;
    }

    private function showEmailForwardHomepage($cookieVals) {
        error_log("then called showEmail function.");
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="pragma" content="nocache">
            <title>Zoe\'s Social Media Project</title>
        </head>
        <body>
            <div>
                <span>Welcome ' . $cookieVals['name'] . '</span>
            </div>
            <form action="http://www.zoes-social-media-project.com/user/login/" method="post">
                <div>
                    <span>Email: ' . $cookieVals['email'] . '</span>
                </div>
                <div>
                    <label for="password">Input your password:</label>
                    <input name="password" type="text">
                </div>
                <div>
                    <button type="submit">Submit</button>
                </div>
            </form>
            <br />
            <div>
                <p>Thank you for joining! Enter your username and password!</p>
            </div>
        </body>
        </html>
        ';
    }

    private function showAuthenticatedHomepage($options) {
        return true;
    }

    private function showPswrdFail($options) {

    }
}