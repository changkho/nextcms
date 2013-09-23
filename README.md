# NextCMS

NextCMS is a next generation of content management system for the enterprises, powered by:

* [Zend Framework 2](http://framework.zend.com)
* [MongoDB] (http://mongodb.org)

## Requirements

* PHP 5.4

NextCMS uses some PHP 5.4 features such as short syntax of array (```[]```), ```trait```

* PHP extensions:

- [intl](http://www.php.net/manual/en/intl.setup.php): Required by ```I18n``` module

## Demo

Coming soon

## Installation

### Configure the web server

NextCMS consists of two sites:
* The main website located at the ```src\web\www``` directory
* The asset website which stores the CSS, Javascript libraries located at the ```src\asset``` directory

Assume that these websites run under ```nextcms.local``` and ```asset.nextcms.local``` domains, respectively.

Use following settings to config nginx web server:

```
server {
    listen      80;
    server_name nextcms.local;

    # Please CHANGE to your directory
    error_log   /Volumes/data/projects_workspace/nextcms/logs/error.log;

    # Please CHANGE to your directory
    root        /Volumes/data/projects/nextcms/src/web/www;
    index       index.php;

    location ~ (\.phtml)$ {
        deny all;
    }
    location / {
        try_files $uri @rewrite;
    }
    location @rewrite {
        rewrite ^/(.*)$ /index.php;
    }
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt { access_log off; log_not_found off; }
    location ~ \.php$ {
        fastcgi_pass                127.0.0.1:9000;
        fastcgi_index               index.php;
        fastcgi_intercept_errors    on;
        fastcgi_param               SCRIPT_FILENAME     $document_root$fastcgi_script_name;
        fastcgi_param               APPLICATION_ENV     dev;
        fastcgi_param               APPLICATION_NAME    nextcms;
        include                     fastcgi_params;
    }
}
```

```
server {
    listen      80;
    server_name asset.nextcms.local;

    # Please CHANGE to your directory
    error_log   /Volumes/data/projects_workspace/nextcms/logs/asset.error.log;

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt { access_log off; log_not_found off; }

    # Please CHANGE to your directory
    root /Volumes/data/projects/nextcms/src/asset;

    location ~* \.(eot|ttf|woff)$ {
        add_header Access-Control-Allow-Origin *;
    }
}
```

## Author

You can contact the author at:

* [phuoc@huuphuoc.me](mailto: phuoc@huuphuoc.me)
* [@nghuuphuoc](http://twitter.com/nghuuphuoc)

## Copyright

Copyright (c) 2013 Nguyen Huu Phuoc

NextCMS is licensed under MIT license.
