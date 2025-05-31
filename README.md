<p align="center">
  <img src="public/assets/logo/logo.svg" alt="SimplePHP Logo" width="200">
</p>

# ⚙️ MicroFramework PHP

Um microframework PHP simples, modular e extensível — ideal para aplicações pequenas ou como base de aprendizado. Inspirado na arquitetura do Laravel, com foco em leveza, organização e produtividade.

## ⚠️ Aviso

> Este projeto ainda está em **desenvolvimento ativo** e **fase de testes**. Não recomendamos seu uso em ambientes de produção ou em projetos de grande porte neste momento.

O **SimplePHP** é ideal para:
- Aprendizado sobre arquitetura MVC e microframeworks PHP
- Prototipagem rápida de ideias
- Pequenos projetos internos ou ferramentas de uso pessoal

Estamos trabalhando para torná-lo mais robusto e confiável, seguindo boas práticas e princípios como **SOLID**, **PSR-4** e código modular.

Sinta-se à vontade para experimentar, testar e colaborar! 🧪🚧

## ✨ Recursos

- ✅ Roteador com suporte a closures e controllers  
- ✅ Manipulação de sessão e mensagens flash  
- ✅ Validação e sanitização de dados  
- ✅ Conexão com banco de dados via PDO  
- ✅ Upload de arquivos seguro  
- ✅ Autoload via Composer com PSR-4  
- ✅ Suporte a variáveis de ambiente (.env)

## 📦 Requisitos

- PHP 8.0 ou superior  
- Composer  
- Servidor local (ex: Apache ou `php -S`)

## 🚀 Instalação

```bash
git clone git@github.com:Justino-code/SimplePHP.git
cd SimplePHP
composer install
cp .env.example .env
php -S localhost:8090 -t public
````

## 🧱 Estrutura Básica

```
my-project/
├── app/
│   ├── Controllers/
│   │   └── HomeController.php
│   ├── Middlewares/
│   │   └── AuthMiddleware.php
│   ├── Models/
│   │   └── User.php
│   └── Views/
│       ├── home.php
│       └── layouts/
│           └── main.php
│
├── routes/
│   └── routes.php          # Rotas definidas pela aplicação
│
├── public/
│   └── index.php           # Ponto de entrada da aplicação
│
├── src/                    # Núcleo do microframework
│   ├── Core/
│   ├── Http/
│   ├── Database/           #Mini-ORM em implementação
│   ├── Validate/
│   └── helpers/
│   └── Deprecated/         # Arquivos antigos
│
├── storage/ # Para o futuro
│   └── logs/
│   └── uploads/
│
├── vendor/                 # Autoload do Composer
│
├── .env                    # Variáveis de ambiente
├── .gitignore
├── composer.json
├── README.md
└── bootstrap.php           # Carregamento da aplicação futuramente (init/init.php)
```

## 🔧 Uso Modular de Componentes (Arquivos Legados)

Apesar do projeto estar em processo de refatoração para aplicar melhores práticas de desenvolvimento — como os princípios **SOLID** e o uso de **Padrões de Projeto** —, ainda é possível utilizar os arquivos da pasta `src/Deprecated/` de forma isolada, especialmente útil para **protótipos**, **sistemas simples**, ou quando há **pressão por tempo**.

Estes arquivos funcionam bem de forma independente e possuem **poucas dependências internas**, facilitando sua reutilização.

### Exemplos de arquivos reutilizáveis:

* `src/Deprecated/Router.php` — sistema de rotas com suporte a controllers, closures e middlewares.
* `src/Deprecated/Request.php` — abstração de requisições com acesso facilitado aos dados.
* `src/Deprecated/Response.php` — resposta HTTP simplificada.
* `src/Deprecated/Model.php` — classe base de ORM com suporte a consultas SQL fluentes, CRUD, relacionamentos, joins, filtros, cache e mais.
* `src/Deprecated/helpers.php` — funções auxiliares úteis como `view()`, `redirect()`, `asset()`, `old()`, entre outras.

> ⚠️ **Atenção:** Estes arquivos estão na pasta `Deprecated` porque fazem parte da **versão anterior** do microframework. Eles continuam **funcionais** e são uma excelente base para estudos ou projetos pequenos. No entanto, **não são recomendados para aplicações grandes ou uso em produção**, pois estamos estruturando o novo núcleo com foco em modularidade, organização e boas práticas.


## 📚 Exemplo de Rota

```php
// routes.php
use Controllers\PostController;

$router->get('/posts', [PostController::class, 'index']);
$router->post('/posts', [PostController::class, 'store'], [AuthMiddleware::class]);
```

---

## 🧠 Uso Temporário da Classe `Model` Base (Deprecated)

Este projeto contém uma pasta chamada `deprecated/`, onde está localizada a versão anterior da classe `Model` base do sistema ORM.

### 📌 Contexto

O sistema foi inicialmente construído com uma classe `Model` robusta, capaz de lidar com:

* Operações CRUD
* Filtros (`where`, `orWhere`, `whereGroup`, etc.)
* Joins e relacionamentos entre tabelas
* Soft deletes
* Cache de queries
* Sanitização de dados
* Geração dinâmica de SQL

No entanto, para melhor aderência aos princípios **SOLID**, iniciou-se uma refatoração modular, dividindo as responsabilidades em classes como:

* `QueryBuilder`
* `QueryExecutor`
* Traits auxiliares (`CrudTrait`, `FiltersTrait`, `RelationsTrait`, etc.)

---

## 📊 Status Atual da Refatoração

A refatoração está em progresso. Abaixo estão os módulos já implementados:

* ✅ `ConnectionManager` (singleton PDO)
* ✅ `QueryBuilder` com composição de:

  * `CrudTrait`
  * `FiltersTrait`
  * `JoinsTrait`
  * `RelationsTrait`
  * `SoftDeletesTrait`
* ✅ `QueryExecutor` como classe separada, SOLID-friendly
* ✅ `CacheHandler` pronto, com estrutura básica
* 🚧 Integração entre `Model` e o novo `QueryBuilder` (em planejamento)
* ⏳ Migração completa dos Models para a nova base modular

---

## 🗺️ Roadmap

| Etapa | Tarefa | Status       |
|-------|--------|--------------|
| 🔄    | Refatorar QueryBuilder em traits reutilizáveis        | Em andamento |
| 🔄    | Criar executor de queries separado (QueryExecutor)    | Em andamento |
| 🔄    | Refatorar lógica de cache, joins, filtros             | Em andamento |
| 🔄    | Unificar QueryBuilder com Model base                  | Em andamento |
| 🔄    | Substituir `deprecated/Model.php` por versão modular  | Em andamento |
| 🔄    | Implementar testes unitários para QueryBuilder e Executor | Em andamento |
| 🔄    | Criar gerador de migrations e seeders                 | Em andamento |
| 🔄    | Gerar documentação técnica de cada componente         | Em andamento |

---

## 🤝 Contribuindo

Contribuições são **muito bem-vindas**! Se você encontrou um bug, deseja sugerir melhorias ou adicionar novos recursos ao microframework, sinta-se à vontade para abrir uma *issue* ou enviar um *pull request*.

### Como contribuir

1. Faça um fork do repositório
2. Crie uma nova branch:
   ```bash
   git checkout -b minha-contribuicao
````

3. Faça suas alterações e commit:

   ```bash
   git commit -m "Melhoria: Adiciona suporte a SoftDeletes"
   ```
4. Envie para o seu repositório remoto:

   ```bash
   git push origin minha-contribuicao
   ```
5. Abra um *Pull Request* com uma breve explicação

### Sugestões de contribuição

* Testes unitários para os componentes internos
* Melhorias na documentação
* Adição de novos helpers
* Middleware de autenticação/token
* Sistema de migrations e seeders

---

Se você gostou do projeto, não esqueça de ⭐ dar uma estrela no repositório e compartilhar com outros devs!

## 📝 Licença

Código aberto sob licença [MIT](LICENSE).