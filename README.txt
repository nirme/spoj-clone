install:

rm -f -r *
wget --no-check-certificate https://github.com/nirme/dominatrix2000/tarball/master
tar xvzf master
rm -f -r master
mv -f nirme-dominatrix2000-	* ./
rm -f -r nirme-dominatrix2000-	
mysql -u projekt5 -pprojekt5 projekt5 < sql/baza.sql
chmod 777 spoj_engine2
chmod 777 studentList
chmod 777 uploads
chmod 777 solutions
