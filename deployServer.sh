#! /bin/sh

ssh nf17p157@tuxa.sme.utc "\
cd /volsme/user1x/uvs/nf17/nf17p157/public_html;\
git stash;\
git checkout master;\
git pull origin master;\
chmod -Rf 755 ./;\
mkdir -p app/log;\
chmod -f 777 app/log;\
exit;";

echo "Cinemato has been deployed !";
