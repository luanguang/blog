1.  官网下载php.zip 7.1版本 修改部分php.ini，下载php_redis到ext下面。可以先查看 php -i当中的Thread Safety：如果是enable，则是Thread Safe（线程安全）版本；否则，就是None Thread Safe（非线程安全）版本。
2.  官网下载composer    修改composer源  
`composer config -g repo.packagist composer https://packagist.phpcomposer.com`
3.  安装git 默认一步到位。
4.  安装docker，默认一步到位。
5.  下载laradock.zip，`docker-compose up -d mysql nginx redis` 遇到PID安装失败再试一次，网络问题救不了。遇到mysql无法连接的问题，用docker-compose ps 命令看到mysql的状态是EXIT 2，到`./laradock/data`下删除`mysql`文件，再重新bild一次。
6.  安装`heidisql`，IP 127.0.0.1 端口 3306 用户 root 密码 root
7.  安装VScode 安装插件
* Auto Rename Tag
* Better PHPUnit
* Code Runner
* File Utils
* Laravel 5 Snippets
* Laravel Artisan
* Laravel Blade Snippets
* Material Icon Theme
* One Dark Pro
* php cs fixer
* PHP Debug
* PHP DocBlocker
* PHP Intelephense
* PHP IntelliSense
* Setting Sync
* Snippet-creator
* Sublime Text Keymap

添加配置
```
{
    "editor.fontFamily": "Fira Code",
    "editor.fontSize": 14,
    "editor.fontLigatures": true,
    "workbench.colorTheme": "One Dark Pro",
    "sublimeTextKeymap.promptV3Features": true,
    "editor.multiCursorModifier": "ctrlCmd",
    "editor.snippetSuggestions": "top",
    "editor.formatOnPaste": true,
    "sync.gist": "b9c0fd6696d0a3c91064ca8260423dbd",
    "sync.lastUpload": "",
    "sync.autoDownload": false,
    "sync.autoUpload": false,
    "sync.lastDownload": "2017-11-27T18:32:42.299Z",
    "sync.forceDownload": false,
    "sync.anonymousGist": false,
    "sync.host": "",
    "sync.pathPrefix": "",
    "sync.quietSync": false,
    "sync.askGistName": false,
    "workbench.iconTheme": "material-icon-theme",
    "git.enableSmartCommit": true,
    "git.confirmSync": false,
    "git.autofetch": true,
    "explorer.openEditors.visible": 0,
    "workbench.activityBar.visible": false,
    "editor.minimap.enabled": false,
    "editor.tabCompletion": true,
    "php-cs-fixer.onsave": true,
    "php-cs-fixer.config": "/Users/branchzero/.vscode/.php_cs",
    "window.openFilesInNewWindow": "on",
    "terminal.integrated.shell.windows": "C:\\Program Files\\Git\\bin\\bash.exe",
}
```

创建新的项目，添加nginx.conf文件，到window\system32\driver\host文件里添加 127.0.0.1 项目名  
重启 docker-compose down  docker-compose up -d mysql redis nginx    
修改.env 文件
