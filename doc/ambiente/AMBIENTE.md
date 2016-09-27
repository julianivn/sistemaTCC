# Configuração de ambiente usando VirtualBox

## Criação e Instalação da Imagem
* Fazer o download da imagem: http://www.ubuntu.com/download/server
* Fazer o download do VirtualBox: https://www.virtualbox.org/wiki/Downloads
 * Instalar o virtual box, ao abrir a interface, clicar em "novo"
* Escolher o nome da VM e selecionar a opção "Criar novo disco rígido virtual agora" 
![img01](img/AD-01.png)
* Na próxima tela, crie o disco virtual com as configurações desejadas
* Ir em Configurações da VM, Armazenamento, Controladora: IDE e escolher a imagem do Ubuntu 
![img02](img/AD-02.PNG)
* Em Rede, selecione a opção Modo Bridge 
![img03](img/AD-03.png)
* Inicie a VM para iniciar a configuração do servidor
* Selecione a linguagem ingles e depois Install Ubuntu Server
* Em "Select your Location", selecion other, após, South America e Brazil
* A próxima tela é referente ao teclado, que é o ponto mais complicado da instalação, primeiramente selecione United States
* Na tela a seguir, selecione yes e siga as instruções na tela
* A próxima tela é a mais importante, você deve selecionar a opção do teclado com 'ç'
* Escolha o nome do servidor
* Escolha o nome do usuario, escolhi user1 
![img04](img/AD-04.png)
* Na próxima tela deixe o mesmo nome.
* Escolha a senha
* Selecione "No" para encriptar o /home
* America/Sao_Paulo deve ser o time zone
* Selecione Guided - use entire disk and set up LVM
* Apenas confirme nas próximas telas, a partir daí o Ubuntu vai ser instalado na VM
* Deixe em branco para o proxy
* Selecione "No automatic updates"
* Se fossemos utilizar o PHP 7, na próxima tela você poderia selecionar o LAMP Server, mas como a vida é difícil, marque apenas openssh server
* Selecione yes, pois será o único Sistema Operacional da VM
* A partir desse momento você já terá o servidor instalado

## Login
* Logue com o usuário que você criou, no meu caso, user1
* Após logar, use o comando sudo -i e digite a senha do user1
* Agora você está logado como root e pode estragar sua VM!
* Execute o comando ifconfig, veja seu IP e use o Putty para se logar 
![img05](img/AD-05.png)
* Logue utilizando o user1 e após logar use o comando sudo -i

## Instalação do Apache
* Para instalar o apache: 
```bash 
$ apt-get install apache2
```
Acesse no seu navegador: http://IP.da.VM para verificar se o apache foi instalado com sucesso

## Instalação do PHP
Para instalar o PHP 5.6.26
```bash
$ sudo add-apt-repository ppa:ondrej/php
$ sudo apt-get update
$ sudo apt-get upgrade
$ sudo apt-get install php5.6
```

## Instalação do Composer
Para instalar o composer:
```bash
$ cd /tmp
$ curl -sS https://getcomposer.org/installer | php
$ chmod +x composer.phar
$ mv composer.phar /usr/local/bin/composer
```

## Instalação do GIT
Instalação do GIT: 
```bash
$ apt-get install git
```

## Instalação do Mod-Rewrite do apache
Siga as instruções dessa página para instalar o mod rewrite do apache: https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite-for-apache-on-ubuntu-14-04

## Instalação do MySQL 5.7

Para instalar o MySQL 5.7 siga as instruções da página: http://www.cyberciti.biz/faq/howto-install-mysql-on-ubuntu-linux-16-04/
