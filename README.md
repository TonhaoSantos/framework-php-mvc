# Framework MVC PHP V1.0.0

Este é um framework mvc php

## Downloads e Instalações obrigatorias

É preciso baixar e instalar Node.JS, NPM, Gulp, Bower e Composer para utilizar este projeto.
Node.JS -> https://nodejs.org

NPM -> https://www.npmjs.com

Gulp -> https://gulpjs.com

Bower -> https://bower.io

Composer -> https://getcomposer.org

#### Etapas

1º Instalar o Nodejs fazendo o download do instalador no site do mesmo.

2º Instalar o NPM globalmente.
   `npm i npm -g`

3º Instalar o GULP globalmente.
   `npm i gulp -g`

4º Instalar o BOWER globalmente.
   `npm i bower -g`

5º Instalar o COMPOSER fazendo o download do instalador no site do mesmo.

6º Baixar as depedencias BOWER. (Depreciado no momento, revendo este passo)
   `bower install`

7º Baixar as dependencias gulp.
   `npm i autoprefixer del express gulp-cache gulp-concat gulp-concat-css gulp-cssnano gulp-dest gulp-htmlmin gulp-if gulp-imagemin gulp-jshint gulp-notify gulp-postcss gulp-prompt gulp-rename gulp-ruby-sass gulp-sourcemaps gulp-uglify gulp-zip inquirer jshint jshint-stylish map-stream merge-stream node-notifier postcss-merge-rules postcss-scss precss rsyncwrapper run-sequence gulp-cache browser-sync gulp gulp-copy`

8º Baixar as dependencias composer, entrar pelo terminal no diretório "http" e rodar o comando.  (Depreciado no momento, revendo este passo)
   `php composer.phar install`


## Atenção
Se você estiver utilizando um repositorio publico, atentar para o arquivo:
 `http > app > database.php`
Pois os seus dados de produção estão presentes no mesmo. Se possivel ignorar no arquivo:
 `.gitignore`


## Base de dados local
Seguir o arquivo base para entender o padrão do projeto.
 `rootProjeto > tips > script > base.sql`
Para criar ou atualizar a sua base local, utilizar sempre o arquivo.
 `rootProjeto > tips > script > banco.sql`

## Utilizar um virtualhost
Tutorial em:
 `tips > virtualhost`




## Criadores

...

## Licença

Software proprietário

## Direitos Autorais

Código e Documentação copyright 2018.
