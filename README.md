# Framework
Ferramenta para dinamizar e padronizar a construção das Aplicações clientes das APIs.

# Instalação
Utilizar o composer.json no gist de exemplo https://gist.github.com/feliphebueno/f1cedecd7e08d05e47e4a2af96f28144.

```PHP
composer install
```
Após a instalação do composer e execução dos scripts informados no composer.json de exemplo, a seguinte estrutura de arquivos terá sido criada:

![app_tree](https://cloud.githubusercontent.com/assets/6662338/21393515/8b2c9e68-c773-11e6-90ac-f434742184a7.jpg)

# Utilização

```PHP
//Criando um novo controller
bin\manage create <nome_controller>

//Utilizando servidor de desenvolvimento
bin\manage run <php.exe|default php> <http_port|default 8081>
```
