FROM graze/php-alpine

RUN apk add --no-cache --repository "http://dl-cdn.alpinelinux.org/alpine/edge/testing" \
    php7-xdebug

ADD . /opt/graze/gigya-client

WORKDIR /opt/graze/gigya-client

CMD /bin/bash
