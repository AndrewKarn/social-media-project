**WORK IN PROGRESS**

Required Depencies to install locally:
* Vagrant
* VirtualBox
* Free Account with [MailGun](https://www.mailgun.com/) to get sandbox API key

Setup:
* Run `vagrant up` in local directory
* Unix Systems: `sudo nano etc/hosts`
    * Add the following entry:
    * `192.168.50.40    www.zoes-social-media-project.com`
    * `control + X` to save.
    * go to `www.zoes-social-media-project.com` to access homepage!
* Create a file called `Key.php` in `util/` with the following format:
```php
<?php
namespace Utility;
class KEY {
    const MAIL_GUN_API_KEY = [your-api-key];
}
```


**Features**
* Automated setup (Nginx configuration, MongoDB configuration, shell scripts)
   *_These can be viewed and edited in_ `util/conf`
* User Account creation including email verification _(Utilizing MailGun API)_
* User authentication via JWT _(work in progress)_
* Front End Controller handles routing of all requests by instantiating `Namespace\Class->Method()` which comes from the request URI 
   * `www.zoes-social-media-project.com/user/login` would call `User\UserController->login()`

## _Stay tuned for weekly updates!_