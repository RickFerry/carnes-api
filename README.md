
---

# Carnês API

## Descrição

A **Carnês API** é uma aplicação RESTful desenvolvida em PHP Laravel, projetada para gerar e apresentar as parcelas de um carnê de pagamento. A API recebe informações como o valor total, a quantidade de parcelas, a data do primeiro vencimento, a periodicidade das parcelas e um valor de entrada opcional. Com esses dados, a API calcula e retorna o valor de cada parcela, respeitando as condições especificadas.

## Funcionalidades

### 1. Criação de um Carnê

Cria um novo carnê e calcula suas parcelas.

- **Endpoint:** `POST /api/carnes`
- **Parâmetros:**
    - `valor_total` (float): O valor total do carnê.
    - `qtd_parcelas` (int): A quantidade de parcelas.
    - `data_primeiro_vencimento` (string, formato YYYY-MM-DD): A data do primeiro vencimento.
    - `periodicidade` (string, valores possíveis: "mensal", "semanal"): A periodicidade das parcelas.
    - `valor_entrada` (float, opcional): O valor da entrada.
- **Resposta:** JSON contendo o valor total, valor de entrada (se houver), e a lista de parcelas com data de vencimento, valor, número da parcela e indicação de entrada.

### 2. Recuperação de Parcelas

Recupera as parcelas de um carnê específico.

- **Endpoint:** `GET /api/carnes/{id}/parcelas`
- **Parâmetros:**
    - `id` (int): O identificador do carnê.
- **Resposta:** JSON contendo a lista de parcelas com data de vencimento, valor, número da parcela e indicação de entrada.

## Cenários de Uso

### Exemplo 1: Divisão de R$ 100,00 em 12 Parcelas Mensais

**Requisição:**
```json
POST /api/carnes
{
  "valor_total": 100.00,
  "qtd_parcelas": 12,
  "data_primeiro_vencimento": "2024-08-01",
  "periodicidade": "mensal"
}
```

**Resposta:**
```json
{
  "total": 100.00,
  "valor_entrada": null,
  "parcelas": [
    { "data_vencimento": "2024-08-01", "valor": 8.33, "numero": 1 },
    { "data_vencimento": "2024-09-01", "valor": 8.33, "numero": 2 },
    ...
    { "data_vencimento": "2025-07-01", "valor": 8.37, "numero": 12 }
  ]
}
```

### Exemplo 2: Divisão de R$ 0,30 em 2 Parcelas Semanais com Entrada de R$ 0,10

**Requisição:**
```json
POST /api/carnes
{
  "valor_total": 0.30,
  "qtd_parcelas": 2,
  "data_primeiro_vencimento": "2024-08-01",
  "periodicidade": "semanal",
  "valor_entrada": 0.10
}
```

**Resposta:**
```json
{
  "total": 0.30,
  "valor_entrada": 0.10,
  "parcelas": [
    { "data_vencimento": "2024-08-01", "valor": 0.10, "numero": 1, "entrada": true },
    { "data_vencimento": "2024-08-08", "valor": 0.10, "numero": 2 },
    { "data_vencimento": "2024-08-15", "valor": 0.10, "numero": 3 }
  ]
}
```

## Configuração do Ambiente

### Pré-requisitos

- **PHP 8.0+**
- **Composer**
- **SQLite**

### Instalação

1. Clone o repositório:

   ```bash
   git clone https://github.com/seu-usuario/carnes-api.git
   cd carnes-api
   ```

2. Instale as dependências:

   ```bash
   composer install
   ```

3. Configure o banco de dados:

    - Crie o arquivo SQLite:

      ```bash
      touch database/database.sqlite
      ```

    - Atualize o arquivo `.env` com a configuração do SQLite:

      ```env
      DB_CONNECTION=sqlite
      DB_DATABASE=/absolute/path/to/your/project/carnes-api/database/database.sqlite
      ```

4. Execute as migrations:

   ```bash
   php artisan migrate
   ```

5. Inicie o servidor:

   ```bash
   php artisan serve
   ```

## Testando a API

Você pode testar a API utilizando ferramentas como Postman ou cURL.

- **Criação de Carnê:**
    - Método: `POST`
    - URL: `http://localhost:8000/api/carnes`
    - Body: JSON com os parâmetros detalhados na seção de funcionalidades.

- **Recuperação de Parcelas:**
    - Método: `GET`
    - URL: `http://localhost:8000/api/carnes/{id}/parcelas`

## Estrutura de Pastas

A estrutura básica do projeto é:

```
carnes-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── CarneController.php
│   ├── Models/
│   │   └── Carne.php
├── config/
├── database/
│   ├── migrations/
│   │   └── 2024_08_20_000000_create_carnes_table.php
│   └── database.sqlite
├── routes/
│   └── api.php
├── composer.json
└── .env
```

## Contribuição

1. Fork este repositório.
2. Crie uma branch para a nova feature (`git checkout -b feature/nome-da-feature`).
3. Commit suas mudanças (`git commit -am 'Adiciona nova feature'`).
4. Push para a branch (`git push origin feature/nome-da-feature`).
5. Abra um Pull Request.

## Licença

Este projeto está licenciado sob os termos da licença MIT.

---
