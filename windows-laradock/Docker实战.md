# Docker

## 基础命令
- 搜索镜像
```
docker search [option] keyword

-f, --filter filter:过滤输出内容；
--format string:格式化输出内容；
--limit int:限制输出结果个数，默认为25个；
--no-trunc:不截断输出结果。

docker search ubuntu
NAME                                                   DESCRIPTION                                     STARS               OFFICIAL            AUTOMATED
ubuntu                                                 Ubuntu is a Debian-based Linux operating sys…   9339                [OK]
dorowu/ubuntu-desktop-lxde-vnc                         Docker image to provide HTML5 VNC interface …   283                                     [OK]
rastasheep/ubuntu-sshd                                 Dockerized SSH service, built on top of offi…   209                                     [OK]
consol/ubuntu-xfce-vnc                                 Ubuntu container with "headless" VNC session…   165                                     [OK]
```

- 获取镜像
```
docker [image] pull NAME[:TAG]

docker pull ubuntu:18.04
18.04: Pulling from library/ubuntu
898c46f3b1a1: Pull complete
63366dfa0a50: Pull complete
041d4cd74a92: Pull complete
6e1bee0f8701: Pull complete
Digest: sha256:017eef0b616011647b269b5c65826e2e2ebddbe5d1f8c1e56b3599fb14fabec8
Status: Downloaded newer image for ubuntu:18.04
```

- 查看镜像信息
```
docker images 

REPOSITORY                                      TAG                 IMAGE ID            CREATED             SIZE
ubuntu                                          18.04               94e814e2efa8        2 weeks ago         88.9MB
laradock_mysql                                  latest              a9f0943fcd5e        3 weeks ago         407MB
laradock_mongo                                  latest              51bc243ba0d9        5 weeks ago         394MB
```

- 删除镜像
```
docker rmi IMAGEA [IMAGE...]

-f, --force:强制删除镜像，即使有容器依赖他；
-no-prune:不要清理未带标签的父镜像

docker rmi ubuntu:18.04
Error response from daemon: conflict: unable to remove repository reference "ubuntu:18.04" (must force) - container a9745f6d4044 is using its referenced image 94e814e2efa8
因为有依赖存在，所以不让删除，那就先删除那个依赖
docker rm a9745f6d4044
docker rmi ubuntu:18.04
Untagged: ubuntu:18.04
Untagged: ubuntu@sha256:017eef0b616011647b269b5c65826e2e2ebddbe5d1f8c1e56b3599fb14fabec8
Deleted: sha256:94e814e2efa8845d95b2112d54497fbad173e45121ce9255b93401392f538499
Deleted: sha256:e783d8ee44ce099d51cbe699f699a04e43c9af445d85d8576f0172ba92e4e16c
Deleted: sha256:cc7fae10c2d465c5e4b95167987eaa53ae01a13df6894493efc5b28b95c1bba2
Deleted: sha256:99fc3504db138523ca958c0c1887dd5e8b59f8104fbd6fd4eed485c3e25d2446
Deleted: sha256:762d8e1a60542b83df67c13ec0d75517e5104dee84d8aa7fe5401113f89854d9
删除成功，当然也可以使用-f强制删除，不推荐这么用
```

- 清理镜像
docker使用久了，会出现一些名字为`<none>`的镜像，这些镜像其实已经没有用了，可以清理掉。
```
docker image prune

-a, -all:删除所有无用镜像，不光是临时镜像；
-filter filter:只清理符合给定过滤器的镜像；
-f, -force:强制删除镜像，而不进行提示确认

docker image prune -f
Deleted Images:
deleted: sha256:c4e61ec60f91c2c1f32f796ed3ece862ba838c2dd3b00600c265a359ed9b4276
deleted: sha256:87af8ab54d7cf84e64cd7366c7ad9d62e8c99676e41bad83d527138f64ee79a9
deleted: sha256:20ca10dd82eca287b0fd3e2a1621d4cb9805bb40d257b6bcb632570bdda9576a
deleted: sha256:2c98d8b768f87763cd8878ef0866fcef7745fd869b4715183f6422f809e68b1c
deleted: sha256:d1f5bbef48c1f8c5041f84a645204a7f2c390e942632cdb602580877f907fd2e
deleted: sha256:a3e5550b586d32e8d085fcf497562d6187ad553eb8455f7843ace5e8807b9904
deleted: sha256:47ff62c4eb21d1487494173031df8d6f6df8467c6583db7393364c1493c58b81
deleted: sha256:d2bac5aac5136166b37e7d0a7da7d13ea93daf09acba696e9fc5cb755e982a59
deleted: sha256:aed6be1ec678b2dbf8df792ad074ade00be573522a9b1c2f95eab043da3f6d9b
deleted: sha256:ef95221d879b6e38a1781a5f083f1c5791034dabdf83fe44cd0dafa7c65dfee1
deleted: sha256:6a72d944781ad44a0177156d7479a4dbde5ee96d1cad0cf4e85538065d1d28a9
deleted: sha256:ae6ae868a55cd365875176b23aa35de858418d06eb6132c54d3220bf3f61d925
```




