
Simple sms sender system which mock two servers with random response to the main server with the ability of cashing unsuccessful call. used technologies:

    php
    symfony
    docker
    nginx
    redis
    mysql
    
__sms-provider__

Send random 200/500 response to simulate real world providers

__sms-simulator__

get request from `http://localhost/sms/send?number=8761&body=test` and send them to providers. Also, handle add failed call to redis and retry to send them whenever servers getting up again. To test this you can change both response on sms-provider to 500

__Report__

Reports are available on `http://localhost/report`

Report sample
![Report](https://i.ibb.co/MStGFT6/Screen-Shot-2019-10-31-at-6-05-39-PM.png)

__Log__

Logs are available on dev-sms.log

__Run bellow commands to run docker__
- docker-compose build
- docker-compose up

Run bellow command to run worker
- docker-compose exec php php bin/console messenger:consume async -vv

