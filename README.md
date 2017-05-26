#api测试管理系统 	试用版

#站点配置
<VirtualHost *:80>
    ServerAdmin cto-ljc@qq.com
    DocumentRoot "/Users/l/Documents/code/web/api_test/api_test_github/public"
    ServerName api_test.com
    RewriteEngine on
    RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-f
    RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /index.php?s=$1 [QSA,PT,L]
</VirtualHost>