<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 4/18/18
 * Time: 5:32 PM
 */

namespace User;


class AccountVerificationView
{
    public function __construct($successfulEmail = false)
    {
        if ($successfulEmail) {
            $this->getCheckEmailPage($successfulEmail);
        } else {
            // TODO implement a standard error page to throw when something doesn't go well.
            echo '<p class="focus-font">error</p>';
        }
    }

    private function getCheckEmailPage($successfulEmail) {
        echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Zoe\'s Project</title>
            </head>
            <body>
                <div class="std-modal ctr">
                    <p class="focus-font">
                        Your verification email has been sent to ' . $successfulEmail . '
                    </p>
                </div>
            </body>
            </html>
            ';
    }

}