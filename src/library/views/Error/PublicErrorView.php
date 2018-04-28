<?php
/**
 * Created by PhpStorm.
 * User: zoerobertson
 * Date: 4/27/18
 * Time: 6:07 PM
 */

namespace Utility;


class PublicErrorView
{
    public function __construct(array $error)
    {
        $this->generatePublicErrorView($error);
    }

    private function generatePublicErrorView($error) {
        if (key($error) === 0 ) {
            $message = '<p>Something went wrong. Please check error logs.</p>';
        } else {
            $message = '<p>
                            An account with '. $error['email'] .' already exists. Please click 
                            <a href="/">here</a> to return to the login screen.
                        </p>';
        }
        echo '
        <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Zoe\'s Social Media Project</title>
                <link href="home-styles.css?version=1" rel="stylesheet" type="text/css">
            </head>
            <body>
                <main>
                    '. $message .'
                </main>
            </body>
        </html>
        ';
        exit();
    }
}