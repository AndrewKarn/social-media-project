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
    public function __construct($version = 'unverified', $cookieVals = [])
    {
        if ($version === 'unverified') {
            error_log('entered unverified page');
            $this->showUnauthenticatedHomepage();
        } elseif ($version === 'emailFwd') {
            error_log("entered emailFwd");
            $this->showEmailForwardHomepage($cookieVals);
        } else {
            error_log("entered authenticated");
            $this->showAuthenticatedHomepage();
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

    private function showAuthenticatedHomepage() {
        return true;
    }
}