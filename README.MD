----------

# Symfony Challenge

# Visão geral do código
- PHP >=8.0.2
- symfony/framework-bundle 6.0.*

## Instalação

Clone o repositório

    git clone https://github.com/eduardoalanonh/Symfony-challenge.git

Mude para a pasta do projeto

    cd Symfony-challenge

Instale todas dependencias do projeto

    composer install

O arquivo .ENV já foi commitado junto, necessario ajustar as credenciais de acesso do banco, precisa ter a base URL pois é injetado em uma parte do código,
no meu caso era o localhost na porta 8000

    DATABASE_URL="mysql://{username}:{password}@127.0.0.1:3306/db_challenge?serverVersion=5.7"
    APP_BASE_URL=http://localhost:8000

Criar o database

    php bin/console doctrine:database:create

Rodar as migrações

    php bin/console make:migration
    php bin/console doctrine:migrations:migrate

Startar o servidor

     symfony server:start                     

É possivel acessar o servidor agora http://localhost:8000

## Rotas criadas

Geração da Hash
    
    GET /make-hash/{input}

Consulta das hashs criadas
    
    GET /hash
com os seguintes query parâmetros, nenhum é obrigatório.
    
    ?less_attempts={quantidade de tentativas}
    ?page={pagina da paginação}
    ?limit={limite de dados nessa consulta}

Ficaria assim http://localhost:8000/hash?less_attempts=5000&page=1&limit=40

## Como executar o Comando

    php bin/console symfony:test new_teste 15  

Onde o new_teste é o input de entrada e o 15 seria a quantidade de vezes que vai realizar o request

    php bin/console symfony:test {INPUT DE ENTRADA} {NUMERO DE REQUESTS}

----------


