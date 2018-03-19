# EdPaste
EdPaste is a Laravel 5.3 (PHP Framework)-driven self-hosted Pastebin. Demo : https://paste.edra.ovh

## Features :
- Privacy options
- Expiration options
- Burn after reading
- Password protection
- User dashboard
- Raw paste viewing

Just git clone this repo on your server, make the `public` folder your webserver's `DocumentRoot`, for instance with an Apache2.4 VirtualHost :
```
<VirtualHost *:80>
    ServerName your.vhost.server.com
    DocumentRoot /app/path/public
</VirtualHost>
```
Run a `composer install`/`php composer install` (depends of your configuration) within the app root path (you'll need composer)
Rename `.env.example` to `.env` and run `php artisan key:generate` from the app's root path.
Open `.env` and fill it with your database details, and with Secret and Site Key from Google's reCaptcha (in order to avoid spam from guests)
Run `php artisan migrate` from the app's root path, and you're all done.

Go to `http://your.vhost.server.com/` which leads to the DocumentRoot `/app/path/public`, and this should work !

# Updating :
Get latest features by pulling this repo again.

In case of updating because of a Laravel version change, delete everything under `bootstrap/cache` and run `composer upgrade`.

# Contributing :
You're free to fork this and modify it as you want (according to MIT license), but please don't remove my name at the bottom of each page. If you look at the code, you'll notice that all the comments are written in french, as I am a french developer and I don't wan't to waste time translating my own comments. If you want english comments instead, feel free to ask it, I'll translate all of these.

# Todo :
- Admin panel
