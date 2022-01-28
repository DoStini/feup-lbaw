# Lbaw2116

# reFurniture

URL to the product: http://lbaw2116.lbaw.fe.up.pt

###  1. Installation

Release version of the source code: https://git.fe.up.pt/lbaw/lbaw2122/lbaw2116/-/tree/PA

Make sure the [env](https://git.fe.up.pt/lbaw/lbaw2122/lbaw2116/-/blob/main/.env) file contains the following credentials in order to be able to receive live notifications using Pusher, checkout with Paypal and emails.

```
APP_NAME=reFurniture
APP_ENV=local
APP_KEY=base64:xWdLc4KY3iJKHCupluHuu1nDwvprk4OAqnsc6RRrGsA=
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=db.fe.up.pt
DB_PORT=5432
DB_SCHEMA=lbaw2116
DB_DATABASE=lbaw2116
DB_USERNAME=lbaw2116
DB_PASSWORD=dKRdEYFN

PUSHER_APP_ID=1330189
PUSHER_APP_KEY=4c7db76f6f7fd6381f0e
PUSHER_APP_SECRET=a21309866f47ce0d4cda
PUSHER_APP_CLUSTER=eu

MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=refurniture.shop@gmail.com
MAIL_PASSWORD=ryrvcrbmqfiynwih
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=mygoogle@gmail.com
MAIL_FROM_ADDRESS=refurniture.shop@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

BROADCAST_DRIVER=pusher
CACHE_DRIVER=file
SESSION_DRIVER=file
SESSION_LIFETIME=120
QUEUE_DRIVER=sync

PAYPAL_SANDBOX_CLIENT_ID=AbaSl9hT2cke42mM567rmeLB6fh29x4oLQlWWO0gnsW1ec3md-VU-7n9WXIvwcFa3X61iT7eAbnmUfD1
PAYPAL_SANDBOX_CLIENT_SECRET=EGYDnmNrcSvzSopxb9P4R24-D6-AeMPlyVDR1BXGP3QptY6ybGwhtC-mbCHWwUEob4vkxf9M2UGZB98F

SESSION_DOMAIN=.localhost
```

Assure that your **docker_run.sh** runs the following commands:

```
#!/bin/bash
set -e

cd /var/www; php artisan config:cache; php artisan storage:link
env >> /var/www/.env
php-fpm8.0 -D
nginx -g "daemon off;"
```

Start the docker container and access localhost:8000:

```
composer install
docker build -t refurniture -f Dockerfile.prod .
docker run -it -p 8000:80 --name=lbaw2116 -e DB_DATABASE="lbaw2116" -e DB_SCHEMA="lbaw2116" -e DB_USERNAME="lbaw2116" -e DB_PASSWORD="dKRdEYFN" git.fe.up.pt:5050/lbaw/lbaw2122/lbaw2116
```

###  2. Usage

####  2.1. Administration Credentials

Administration URL: http://lbaw2116.lbaw.fe.up.pt/admin

| E-mail | Password |
|--------|----------|
| admin@refurniture.com | password |

####  2.2. User Credentials
| Type | E-mail | Password |
|------|--------|----------|
| basic account | hcalvert4@wisc.edu | password |

#### 2.3 Paypal Checkout Credential
| E-mail | Password |
|--------|----------|
| nuno@refurniture.com | 6vyjV-}M |


# Members:

-   André Júlio Moreira - up201904721@edu.fe.up.pt
-   João Baltazar - up201905616@edu.fe.up.pt
-   Nuno Costa - up201906272@edu.fe.up.pt
-   Nuno Miguel da Silva Alves - up201908250@edu.fe.up.pt
