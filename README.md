# Framework
Ferramenta para dinamizar e padronizar a construção das Aplicações clientes das APIs.

# Instalação
Utilizar o composer.json no gist de exemplo https://gist.github.com/feliphebueno/f1cedecd7e08d05e47e4a2af96f28144.

Estrutura e arquivos necessários para iniciar a APP:

![app_init](https://cloud.githubusercontent.com/assets/6662338/21555777/f214e6e4-cdf9-11e6-8b6c-9770b47abfc3.jpg)

```PHP
composer install
```

Após a instalação do composer e execução dos scripts informados no composer.json de exemplo, a seguinte estrutura de arquivos terá sido criada:

![app_instalada](https://cloud.githubusercontent.com/assets/6662338/21399150/3441eaca-c789-11e6-96b4-b9903b052a89.jpg)

# Utilização

```PHP
//Criando um novo controller
bin\manage create <nome_controller>

//Utilizando servidor de desenvolvimento
bin\manage run <php.exe|default php> <http_port|default 8081>
```
# Acesso

### Via apache
http://localhost/App/

### Server de desenvolvimento
http://localhost:{http_port}/App/

# Done
Agora implemente seus controllers e seja feliz :)
