php extension for bakmimakyus:
gd
imagick

laravel link storage:
FILESYSTEM_DISK=public	(env)
php artisan storage:link

setelah hosting, set .env appurl dan di web midtrans
 set notification url dan finish redirect url /payment/success

 setupcronjob untuk update status pembayaran dari metode cash  * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
