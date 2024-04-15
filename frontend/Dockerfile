FROM node:lts-alpine3.18 AS base

RUN --mount=type=cache,target=/usr/src/app/.npm \
  npm set cache /usr/src/app/.npm && \
  npm install -g @angular/cli@17

USER node

WORKDIR /home/node/app

COPY --chown=node:node ./frontend/package*.json .

RUN npm install

COPY --chown=node:node ./frontend .

RUN rm ./README.md Dockerfile .gitignore .editorconfig

RUN rm -rf ./.vscode

FROM base AS dev

EXPOSE 4200

CMD ["ng", "serve", "--host", "0.0.0.0"]

FROM base AS build

RUN ng build

FROM nginx:1.25.1-alpine AS prod

COPY --from=build /home/node/app/dist /usr/share/nginx/html

COPY ./deployment/config/nginx/frontend.conf /etc/nginx/nginx.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
