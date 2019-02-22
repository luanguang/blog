a) 打开 .env 文件

b) 在Workspace Container下搜索WORKSPACE_INSTALL_MONGO 参数

c) 将其设置为 true

d) 在PHP-FPM容器下搜索PHP_FPM_INSTALL_MONGO 参数

e) 将其设置为 true

2 - 重构容器 docker-compose build workspace php-fpm

3 - 使用 docker-compose up 命令运行MongoDB Container（mongo）。

`docker-compose up -d mongo`

然而`docker-compose ps`却发现
```
laradock_mongo_1          docker-entrypoint.sh mongod      Exit 14
```
这种情况下，你可以尝试一下把docker-compose.yml文件里的mongoDB的路径地址改一下
```
### MongoDB ##############################################
    mongo:
      build: ./mongo
      ports:
        - "${MONGODB_PORT}:27017"
      volumes:
        #- ${DATA_PATH_HOST}/mongo:/data/db
        - ${DATA_PATH_HOST}/mongo:/data/mongodb
      networks:
        - backend
```