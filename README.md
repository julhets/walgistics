#Walgistics API - @Julio Reis

##A REST API for Walgistics Service.

* Arquitetura
    - O framework utilizado para desenvolvimento da API foi o Silex, que é baseado em Symfony;
        - A estrutura está bem enxuta com o básico para rodar o serviço;
        - Seguindo o padrão Silex, temos os "Providers" (roteadores), "Repositories" (acesso ao banco), "Resources" (lógica da aplicação) e os "ValueObjects" (entidades).

    - O banco de dados utilizado é o Mysql e o servidor de aplicação foi o próprio embedd do Silex.

* Instalação
    1. Ao descompactar o .zip, a pasta da aplicação "walgistics" conterá o arquivo install.sh;
    2. O install.sh instalará todas as dependências do projeto (git, composer, PHP, e extensões do PHP);
    3. Será necessário criar um banco de dados com o nome "walgistics". O endereço do host, usuário e senha evem ser configurados no arquivo: "walgistics/config/dev/db.php"
    4. Execute o comando: "composer install" na pasta raiz da aplicação;
    5. Execute o comando: "vendor/bin/doctrine orm:schema-tool:create" na raiz da aplicação - Esse comando criará as entidades no banco de dados;
    5. Execute o start.sh (que também se encontra na raiz da aplicação). Ele iniciará o servidor de aplicação e deverá ser executado dentro da pasta raiz da aplicação;
    6. Teste a API acessando: http://localhost:8999
    7. A API também está pública em: http://walgistics.rapidets.com.br/

* Requerimentos (incluídos no install.sh)
    - PHP 5
        -apt-get install php5-common php5-dev php5-cli php5-fpm

    - Extensões do PHP5
        -apt-get install curl php5-curl php5-gd php5-mcrypt php5-mysql php-apc

    - MySql
        - apt-get install mysql-server

    - Composer
        -curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

* End-Points do serviço
    Os end-points são restfull com o media-type application/json.

    1. GET /map
        * Exibe uma msg de boas vindas à API;
        * Exemplo de formato:
            - {"walgistics":"Welcome to Walmart Logistics API."}

    2. POST /map
        * Cadastra um novo mapa e seus trajetos;
        #Request
            * Exemplo de formato:
                - {"name":"Map 1", "routes":[["A","B",10],["B","D",15],["A","C",20],["C","D",30],["B","E",50],["D","E",30]]}
            * Exemplo de request através do CURL via terminal:
                - curl -H "Content-Type: application/json" -X POST --data '{"name":"Map 1", "routes":[["A","B",10],["B","D",15],["A","C",20],["C","D",30],["B","E",50],["D","E",30]]}' http://walgistics.rapidets.com.br/map

        #Response
            * Exemplo de formato:
                - {"id":2}

    3. POST /map/route
        * Pesquisa a melhor rota de acordo com os parâmetros passados;
        #Request
            * Exemplo de formato:
                - {"mapName":"Map 1","from":"A","to":"D","autonomy":10,"fuelPrice":2.5}
            * Exemplo de request através do CURL via terminal:
                - curl -H "Content-Type: application/json" -X POST --data '{"mapName":"Map 1","from":"A","to":"D","autonomy":10,"fuelPrice":2.5}' http://walgistics.rapidets.com.br/map/route
        #Response
                    * Exemplo de formato:
                        - {"totalCostValue":6.25,"route":"ABD"}


* Sobre o exercício:
    # Achei o exercício bem desafiador em relação à descoberta de um algoritmo com melhor performance possível. No caso do Walmart, acredito que uma simples vírgula a mais pode impactar bastante.