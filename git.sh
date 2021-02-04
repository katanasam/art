#git.sh
#Simplification du processus Git


git status 
git add . 
echo "entrer une valeur pour le commit"
read txt_com
git commit -m "$txt_com"

git status
echo "ending script"