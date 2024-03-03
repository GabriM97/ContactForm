# Contact Form

This project consists in a modern contact form using Vue.js and Laravel.

## Install Composer dependencies

```shell
docker run --rm --interactive --tty --volume $PWD:/app composer install --ignore-platform-reqs
```

## Configure the `.env` file

Copy the `.env.example` file and create the `.env` file.

```shell
cp .env.example .env
```

It should be enough to start the project. Customise it, eventually, based on your needs.

## Start the project

This projects uses "sail", a Laravel library for integrating with docker.

Use sail to start up the containers.

``` shell
./vendor/bin/sail up
```

or if you want to start the containers in background:

```shell
./vendor/bin/sail up -d
```

**NOTE:** If you need to run the sail command using `sudo` add the following two variables to your `.env` file otherwise you might get permissions issues:
```env
WWWUSER=1000
WWWGROUP=1000
```
This is needed because sail will run as `root` user (which is always `uid=0; gid=0`) and will set `WWWUSER=0` and `WWWGROUP=0` when building the image.
"1000" should be the value of your user UID and GID. You can double check if this is correct by running:
- `id -u $(whoami)` - the UID (user) value
- `id -g $(whoami)` - the GID (group) value

Replace the values in the `.env` file if needed.