# Build env
FROM node:lts-alpine as build-npm-stage

WORKDIR /app
COPY package*.json ./
COPY vite.config.js ./
RUN npm install
COPY index.html ./
COPY public ./public
COPY src ./src

EXPOSE 5173

CMD [ "npm", "run", "dev", "--", "--host", "0.0.0.0" ]