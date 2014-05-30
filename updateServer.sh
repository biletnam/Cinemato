#! /bin/sh

ssh nf17p157@tuxa.sme.utc "\
cd /volsme/user1x/uvs/nf17/nf17p157/public_html;\
git stash;\
git checkout master;\
git pull origin master;\
php composer.phar self-update;\
php composer.phar update --no-dev;\
chmod -R 755 ./;\
exit;";

echo "Cinemato is up-to-date !";
