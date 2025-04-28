FROM node:21-alpine

RUN apk add git

COPY entrypoint.sh /entrypoint
ENTRYPOINT ["/entrypoint"]

RUN mkdir -p /.npm && chown -R 1000:1000 /.npm && chmod -R 777 /.npm
USER 1000
