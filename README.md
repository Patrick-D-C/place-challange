
# Places API

Esta é uma API REST desenvolvida com **Laravel 10**, **PHP 8.3**, **PostgreSQL**, **Docker** e **Pest**, com o objetivo de gerenciar lugares (CRUD).  
O projeto segue boas práticas de arquitetura, versionamento de API, testes automatizados e validações estruturadas.

---

# 🚀 Tecnologias Utilizadas

- Laravel 10
- PHP 8.3 (FPM)
- PostgreSQL 15
- Nginx
- Docker & Docker Compose
- API Resources
- Request Validation
- Service Layer (boas práticas opcionais)

---

# 📁 Estrutura do Projeto

app/
  Http/
    Controllers/Api/PlaceController.php
    Requests/
      StorePlaceRequest.php
      UpdatePlaceRequest.php
    Resources/
      PlaceResource.php
  Models/
    Place.php
  Services/
    PlaceService.php

database/
  migrations/
  factories/

routes/
  api.php

docker/
  php/Dockerfile
  nginx/default.conf

---

# 🏗 Funcionalidades Implementadas

- CRUD de lugares  
- Filtros avançados (name, city, state)  
- Ordenação (`sort=name`, `sort=-created_at`)  
- Paginação (`page`, `per_page`)
- Slug automático e único  
- Respostas padronizadas em JSON  
- Tratamento de erros estruturado  
- Testes automatizados (incluindo cenários de falha)  
- Factories para criação de massa de dados  

---

# 🐳 Executando o Projeto com Docker

## 1. Clone o repositório
git clone <url-do-repositorio>
cd <pasta-do-projeto>

## 2. Configure o arquivo `.env`
cp .env.example .env

## 3. Suba os containers
docker-compose up -d --build

## 4. Entre no container
docker-compose exec app bash

## 5. Instale dependências e rode migrações
composer install
php artisan key:generate
php artisan migrate

API disponível em → http://localhost:8000

---

# 📚 Endpoints da API

## Criar Lugar  
POST /api/places

Body:
{
  "name": "Praça XV",
  "slug": "praca-xv",
  "city": "Florianópolis",
  "state": "SC"
}

---

## Listar Lugares  
GET /api/places

Parâmetros opcionais (query string):

| Param | Exemplo | Descrição |
|------|---------|-----------|
| name | Praça | Busca parcial |
| city | Florianópolis | Cidade |
| state | SC | Estado |
| sort | name / -created_at | Ordenação (prefixe com `-` para desc). Campos permitidos: id, name, city, state, created_at, updated_at. Padrão: `-created_at`. |
| page | 2 | Página atual (padrão: 1) |
| per_page | 20 | Itens por página (padrão: 10, máximo: 100) |

Resposta paginada segue o padrão Laravel API Resource + meta:
```json
{
  "data": [
    {
      "id": 1,
      "name": "Praça XV",
      "slug": "praca-xv",
      "city": "Florianópolis",
      "state": "SC",
      "created_at": "2024-01-01T12:00:00Z",
      "updated_at": "2024-01-01T12:00:00Z"
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/places?page=1",
    "last": "http://localhost:8000/api/places?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "path": "http://localhost:8000/api/places",
    "per_page": 10,
    "to": 1,
    "total": 1
  }
}
```

---

## Obter um Lugar  
GET /api/places/{id}

---

## Obter por Slug  
GET /api/places/slug/{slug}

---

## Atualizar Lugar  
PUT /api/places/{id}

---

## Deletar Lugar  
DELETE /api/places/{id}

Retorno: 204 No Content

---

# 🧪 Testes Automatizados

Para rodar testes:
php artisan test

Inclui testes de:
- Criação  
- Listagem com filtros  
- Validação (422)  
- Atualização  
- Exclusão  
- Slug único  
- Busca por slug  

---

## 👨‍💻 Desenvolvedor

**Patrick Deitós Cremonese**

Projeto desenvolvido para fins de aprendizado, demonstração técnica e avaliação de boas práticas em front-end moderno.

---

## 📄 Licença

Projeto livre para fins educacionais.
