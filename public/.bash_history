ls
php artisan migrate --seed
cd ela_gestion_laravel-ozhwvsxd.on-forge.com 
ls
php artisan migrate --seed
/home/forge/ela_gestion_laravel-ozhwvsxd.on-forge.com/current
ls
cd current
ls
php artisan migrate --seed
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan migrate --seed
tail -n 100 storage/logs/laravel.log
cd ..
ls
cd ..
ls
cd lugaria-kynntkb5.on-forge.com 
ls
cd current
ls
php artisan migrate --seed
php artisan route:cache
php artisan route:list --name=register
php artisan route:list | grep register
php artisan route:list --name=admin.estadisticas
In AbstractRouteCollection.php line 248:
=> Deployment failed: An unexpected error occurred during deployment.
php artisan route:list --name=logout
exit
ls
cd lugariaprop.com
ls
cd current
ls
tail -n 80 storage/logs/laravel.log
php artisan optimize:clear
php artisan migrate:fresh --seed
pwd
ls -la
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan tinker
cd /home/forge/lugariaprop.com/current
php artisan tinker --execute="dump(config('mail.default'), config('mail.mailers.smtp.host'), config('mail.from.address'))"
grep '^MAIL_' .env
php artisan tinker --execute="dump(config('mail.default'), config('mail.from.address'))"
cd /home/forge/lugariaprop.com
readlink -f current
ls -la .env current/.env
grep '^MAIL_' .env
grep '^MAIL_' current/.env
curl ifconfig.me
exit
ls
php -m | grep -i sodium
php --ri sodium
php8.4 -m | grep -i sodium
php8.4 --ri sodium
exit
ls
sudo apt update
ls
sudo apt update
exit
ls
cd lugariaprop.com
ls
cd current
ls
php artisan migrate:fresh --seed
php artisan db:seed --class=ProveedorSeeder
exit
ls
cd ela_gestion_laravel-ozhwvsxd.on-forge.com
ls
cd current
ls
php artisan migrate:fresh --seed
exit
ls
cd lugariaprop.com
ls
cd current
ls
php artisan migrate
exit
ls
cd lugariaprop.com
ls
cd current
ls
php artisan cache:clear
exit
ls
cd multiwebs-rtvt2eaq.on-forge.com
ls
cd current
ls
php artisan db:seed
exit
ls
cd ws_ela-kjofurhu.on-forge.com
ls
cd current
ls
php artisan db:seed
exit
