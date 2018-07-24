# Clone of Spoj platform, for use for students in groups.

# Used PHP/MySQL for most part, C++ with Qt4 for working with Excel files convertion and running tasks.


# install:

rm -f -r *
wget --no-check-certificate /URL/
tar xvzf master
rm -f -r master
mv -f /my_catalogue/	* ./
rm -f -r /my_catalogue/
mysql -u projekt5 -pprojekt5 projekt5 < sql/baza.sql
chmod 777 spoj_engine2
chmod 777 studentList
chmod 777 uploads
chmod 777 solutions
