# sistemaTCC
Sistema para Controle e Acompanhamento de Trabalhos de Conclusão de Curso

## Pré-requisitos
* [Git](https://git-scm.com)
* [PHP](http://php.net) 5.5
* [Composer](https://getcomposer.org)
* [MySQL](https://www.mysql.com)
* [Bower](http://bower.io)

## Como iniciar (Utilizando linha de comando)
Para colaborar você deve primeiro clonar o projeto do [GitHub](https://github.com/Ulbra-Gravatai/sistemaTCC/):
```
git clone https://github.com/Ulbra-Gravatai/sistemaTCC.git
cd sistemaTCC
```
Então, você deve utilizar o [Composer](https://getcomposer.org) para instalar as dependências:
```
composer install
```
Após os passos acima, atualmente você terá a seguinte estrutura de diretórios:

## Estrutura de diretórios

```
sistemaTCC
|
|-- /doc
|   |-- /analysis
|
|-- /public
|   |-- /app
|   |-- /lib
|   |-- .htaccess
|   |-- index.php
|
|-- /src
|   |-- /SistemaTCC
|       |-- /Application
|       |   |-- SistemaTCC.php
|       |
|       |-- /Controller
|       |   |-- AlunoController.php
|       |   |-- IndexController.php
|       |   |-- ProfessorController.php
|       |   |-- SemestreController.php
|       |
|       |-- /Model
|       |   |-- Aluno.php
|       |   |-- Pessoa.php
|       |   |-- Professor.php
|       |   |-- Semestre.php
|       |
|       |-- /View
|           |-- /aluno
|           |   |-- formulario.twig
|           |   |-- listar.twig
|           |
|           |-- /index
|           |   |-- creditos.twig
|           |   |-- index.twig
|           |
|           |-- /professor
|           |   |-- formulario.twig
|           |   |-- listar.twig
|           |
|           |-- /semestre
|               |-- formulario.twig
|               |-- listar.twig
|
|-- /vendor
|    |-- /composer
|    |-- /pimple
|    |-- /psr
|    |-- /silex
|    |-- /symfony
|    |-- /twig
|    |-- autoload.php
|
|-- .bowerrc
|-- .gitignore
|-- bower.json
|-- composer.json
|-- LICENCE
|-- README.md
```

## Criando sua branch
Para começar a brincar, crie sua própria branch para não haver problemas na hora de fazer um git push, lembre-se de substituir <seu_nome> por você sabe o que...
```
git checkout -b <seu_nome>
```

## Brincadeira!
Agora sim, a brincadeira pode começar!

## Logo mais...
Em seguida colocaremos como utilizar
* [PHP](http://php.net)
* [Composer](https://getcomposer.org)
* [Silex](http://silex.sensiolabs.org)
* [Twig](http://twig.sensiolabs.org)
* [Doctrine ORM](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/)
* [Git](https://github.com)
* [GitHub](https://github.com)
* [MySQL](https://www.mysql.com)
* [Bower](http://bower.io)
