# Teste John lennon de Melo Fernandes

A api de validação de dna de mutantes foi desenvolvida utilizando Zend expressive, php 7.2, mongodb e docker + docker-compose.

- Para executar o projeto é necessário ter o docker e o docker-compose.

Após clonar o projeto é necessário instalar as dependencias por isso execute o comando:  `docker-compose run composer install`


Após o termino da instalação das dependências do projeto, execute o comando `docker-compose up -d` na raiz do projeto  para executar o build e subir os containers docker.
Após a instalação a api estará disponível no endereço `http://localhost:8888`
- Os Endpoints são: 

POST = `http://localhost:8888/mutant`

*body*: 
```json
{
    "dna":[
        "ATGCGA",
        "CAGTGC",
        "TTATGT",
        "AGAAGG",
        "CCCCTA",
        "TCACTG"
    ]
}
```

GET = `http://localhost:8888/stats` para resgatar estatísticas.

Há ainda um endpoint adicional que foi muito utilizado em tempo de desenvolvimento

POST = `http://localhost:8888/generate-fake-data/100` aonde 100 é a quantidade de registros que desejamos criar.

# Testes
- Para rodar os testes unitário rode o comando, `composer test`;

- Para gerar os relatório de cobertura rode comando `composer test-coverage`;
  
  
# Cloud

A versão cloud foi hospedada na minha instância nano gratuita da amazon e a url para a api encontra-se no seguinte endereço de `http://magneto.letmehelpyou.com.br` ( o domínio é  meu ).

Ps. explorem meu github, há bastante projetos legais, inclusive o meu chess em canvas que está hospedado aqui mesmo /jl91/chess.

