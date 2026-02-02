@echo off
REM PHP backend'i başlat
start cmd /k "php -S localhost:8000 -t src/php"

REM Next.js custom server'i başlat
start cmd /k "nodemon server.js"