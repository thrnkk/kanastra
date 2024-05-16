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