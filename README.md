# wing
store application backend api configuration steps

Setting up wing.com domain in localhost =>

Do the following entry in /etc/hosts file

127.0.0.1       wing.com

copy the attached file => 'wing.com.conf' in the following location
/etc/apache2/sites-enabled/

make a directory named as 'wing.com' in the following location 
/var/www/

now run "cd /var/www/wing.com"
then run there "ln -s path-to-project-folder public_html"

please refer to this site once if any issues in setting up domain
https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-14-04-lts

dump the store.sql file attached in the mail
username of mysql => root
password of mysql => root
servername => localhost
you can change them as per you need by accessing the api/v1/constants.php in the project folder

You can use helper functions in api/helper/encrypt.php to get the authID's for new users

Auth ID's already generated for testing =>

Auth ID for user wing92 is => 9hZ3VWySBtE=
Auth ID for user samtron92 is => En6D/IR1tFqgQFOOrVYzEA==
Auth ID for user shourya92 is => lHzH3HI5AFXz+ww+e8UCXQ==

Note => You can ignore padding '=' characters at the end  

Please revert if any issues in setup - sb.iiita15@gmail.com
