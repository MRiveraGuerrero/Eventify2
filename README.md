# Docs
[Google Drive](https://drive.google.com/drive/folders/1gwj_1JCwaeasd_a4QrthVIfOb-2m5Ynl?usp=drive_link)

# Integrantes 
Ander Gorocica, Mikel Rivera, Diego Nido, Unax Zardoya

# Docker LAMP
Linux + Apache + MariaDB (MySQL) + PHP 7.2 on Docker Compose. Mod_rewrite enabled by default.

## Instructions

If this is the first time you are using this, you must build "web" (docker image) first
```bash
$ docker build -t="web" .
```

Enter the following command to start your containers:
```bash
$ docker-compose up -d
```

To stop them, use this:
```bash
$ docker-compose stop
```

The database.sql file will be imported when you create the containers for the first time.

If you are looking for phpMyAdmin, take a look at [this](https://github.com/celsocelante/docker-lamp/issues/2).
