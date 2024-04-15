FROM node:20.12.2-alpine3.19 AS base

RUN --mount=type=cache,target=/usr/src/app/.npm \
  npm set cache /usr/src/app/.npm && \
  npm install -g @angular/cli@17

WORKDIR /home/node/app

COPY --chown=node:node ./frontend/package*.json .

FROM base AS dev

RUN --mount=type=cache,target=/usr/src/app/.npm \
  npm set cache /usr/src/app/.npm && \
  npm install

USER node

COPY --chown=node:node ./frontend .

EXPOSE 4200

CMD ["ng", "serve", "--host", "0.0.0.0"]

FROM base AS build

ENV NODE_ENV production
RUN --mount=type=cache,target=/usr/src/app/.npm \
  npm set cache /usr/src/app/.npm && \
  npm ci --only=production

USER node
COPY --chown=node:node ./frontend .
RUN ng build # no debería ser CMD ["ng", "build"] 

FROM nginx:1.25.1-alpine AS prod

COPY --from=build /home/node/app/dist /usr/share/nginx/html
COPY ./deployment/config/nginx/frontend.conf /etc/nginx/nginx.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
