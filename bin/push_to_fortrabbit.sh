ssh www-template@deploy.eu2.frbit.com php artisan down
ssh www-template@deploy.eu2.frbit.com php artisan config:clear
ssh www-template@deploy.eu2.frbit.com php artisan route:clear
git remote add fortrabbit www-template@deploy.eu2.frbit.com:www-template.git
git push fortrabbit master
scp -r ./public www-template@deploy.eu2.frbit.com:/srv/app/www-template/htdocs
ssh www-template@deploy.eu2.frbit.com php artisan config:cache
ssh www-template@deploy.eu2.frbit.com php artisan route:cache
ssh www-template@deploy.eu2.frbit.com php artisan view:clear
ssh www-template@deploy.eu2.frbit.com php artisan migrate --force
ssh www-template@deploy.eu2.frbit.com php artisan sitemap:generate
ssh www-template@deploy.eu2.frbit.com php artisan up
