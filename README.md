-Documentation QCMSIO-

-RECOMMANDATIONS-

  -Php-
  
  -Il faut Php en version 8.2.7 pour mettre en place le QCMSIO.

  -Symfony-
  
  -Symfony doit être en version 7.0.5 pour l'application QCMSIO.

  -Sqlite-
  
  -Sqlite doit être utilisable pour la base de données QCMSIO.

  -Github-
  
  -Github sera nécessaire pour récupérer le projet QCMSIO.
  
  -Php.ini-
  retirer le ; avant ces lignes : 
  
    extension=ldap
    extension=curl
    extension=ffi
    extension=ftp
    extension=fileinfo
    extension=gd
    extension=gettext
    extension=gmp
    extension=intl
    extension=imap
    extension=mbstring
    extension=exif      ; Must be after mbstring as it depends on it
    extension=mysqli
    extension=oci8_12c  ; Use with Oracle Database 12c Instant Client
    extension=oci8_19  ; Use with Oracle Database 19 Instant Client
    extension=odbc
    extension=openssl
    extension=pdo_firebird
    extension=pdo_mysql
    extension=pdo_oci
    extension=pdo_odbc
    extension=pdo_pgsql
    extension=pdo_sqlite
    extension=pgsql
    extension=shmop

    Ligne 920 à 944

-MISE EN PLACE DU PROJET-

  -Récupérer le projet -> dans un CMD tappez : git -clone https://github.com/LaDetox87/QCMSIO.git
  
  -Installer les packages -> dans un CMD tappez : composer install
  
  -Lancer le serveur symfony -> dans un CMD tappez : symfony server:start
  
  -Accéder à l'url donnée dans le CMD.
