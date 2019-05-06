API Etna
Pour l'Authentification:

@PARTIE 1

Au cas ou il yaura une demande d'installation de packet

`composer require laravel/passport`

`composer require laravel/socialite`

Premierement

`php artisan migrate`

Après chaque migrate ou refresh, il Faut refaire cette commande

`php artisan passport:install`

ou s'il ya des problèmes, il faudra forcer

`php artisan passport:install --force`
 

Dans .env pour envoyer un mail:

->`MAIL_DRIVER=sendmail`

->`MAIL_HOST=smtp.gmail.com`

->`MAIL_PORT=465`

->`MAIL_USERNAME=Your_mail`

->`MAIL_PASSWORD=Your_Password`

->`MAIL_ENCRYPTION=`

Envoie de Mail

`GOOGLE_ID=your_id`

`GOOGLE_SECRET=your_secret`

`GOOGLE_URL=Lien Callback`

`FACEBOOK_ID=your_id`

`FACEBOOK_SECRET=your_secret`

`FACEBOOK_URL=Lien Callback`