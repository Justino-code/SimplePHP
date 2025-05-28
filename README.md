# Microframework PHP - Base para Projetos Web Pequenos

Bem-vindo ao microframework PHP desenvolvido para aplicações web pequenas e médias. Este sistema fornece uma base leve, extensível e organizada para construir APIs ou sistemas administrativos com recursos como roteamento, validação, sessões, manipulação de arquivos, banco de dados e mais.

---

## 📁 Estrutura do Projeto

```bash
src/
|
├── Core/
│   ├── Request.php        # Lida com dados da requisição HTTP
│   ├── Response.php       # Classe para respostas HTTP
│   ├── Router.php         # Roteador principal
│   ├── Session.php        # Manipulação de sessão
│   └── Validator.php      # Validação e sanitização de dados
│
├── Database/
│   └── Database.php       # Conexão com banco de dados via PDO
│
├── Http/
│   └── UploadedFile.php   # Manipulação de arquivos enviados
│
├── Controllers/
│   └── PostController.php # Exemplo de controller
│
├── Models/
│   └── Post.php           # Exemplo de model
│
├── routes.php             # Definição de rotas
├── bootstrap.php          # Inicialização da aplicação
```

---

## 📦 Classes Principais

### 🔹 `Src\Request`

Classe que lida com dados de entrada da requisição HTTP.

#### Métodos principais:

* `method(): string`
* `get(string $key, ?string $default = null): ?string`
* `post(string $key, ?string $default = null): ?string`
* `all(): array`
* `file(string $key): ?UploadedFile`
* `isPost(): bool`
* `isAjax(): bool`
* `validateCsrf(): void`
* `csrfToken(): ?string`
* `segments(): array`
* `segment(int $index, ?string $default = null): ?string`
* `uri(): string`
* `ip(): string`

---

### 🔹 `Src\Response`

Classe utilitária para gerar respostas HTTP e redirecionamentos.

#### Métodos principais:

* `redirect(string $url): RedirectResponse`
* `json(array $data, int $code = 200): void`

---

### 🔹 `Src\Session`

Manipula dados de sessão e mensagens temporárias.

#### Métodos principais:

* `get(string $key, $default = null): mixed`
* `set(string $key, $value): void`
* `flash(string $key, $value): void`
* `getFlash(string $key): ?string`
* `destroy(): void`

---

### 🔹 `Src\Validator`

Sanitiza e valida dados recebidos do usuário.

#### Métodos principais:

* `sanitizeString(string $input): string`
* `validateEmail(string $email): bool`
* `validatePassword(string $password): bool`

---

### 🔹 `Src\UploadedFile`

Classe para lidar com arquivos enviados via formulário.

#### Métodos principais:

* `getClientOriginalName(): string`
* `getClientMimeType(): string`
* `getSize(): int`
* `move(string $directory, string $newName = null): bool`

---

### 🔹 `Src\Database`

Classe para conexão com banco de dados via PDO.

#### Métodos principais:

* `getConnection(): PDO`
* `execute(string $sql): void`
* `prepare(string $sql): PDOStatement`

> ✅ A lógica de criação de tabelas/usuários não está embutida por padrão, incentivando uso de migrations externas.

---

## 🔐 Segurança

* Validação de **CSRF Token** para formulários.
* **Sanitização de entradas** com `Validator`.
* Sessões protegidas com `Session`.

---

## 🚀 Como Começar

1. Configure o arquivo `.env` com as informações do seu banco de dados:

```
DB_HOST=localhost
DB_NAME=meu_banco
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4
```

2. Execute o servidor local embutido:

```bash
php -S localhost:8000 -t public
```

3. Acesse no navegador: [http://localhost:8000](http://localhost:8000)

---

## 📊 Exemplo de Uso

### 🔹 Controller (PostController.php)

```php
namespace Controllers;

use Src\Request;
use Src\Response;
use Models\Post;

class PostController
{
    public function index()
    {
        $posts = (new Post)->all();
        return Response::json($posts);
    }

    public function store(Request $request)
    {
        $request->validateCsrf();
        $dados = $request->all();
        (new Post)->create($dados);
        return Response::redirect('/posts');
    }
}
```

---

### 🔹 Model (Post.php)

```php
namespace Models;

use Src\Database;
use PDO;

class Post
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database)->getConnection();
    }

    public function all(): array
    {
        return $this->db->query("SELECT * FROM posts")
                         ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): void
    {
        $stmt = $this->db->prepare("INSERT INTO posts (titulo, conteudo) VALUES (:titulo, :conteudo)");
        $stmt->execute($data);
    }
}
```

---

### 🔹 Rotas (routes.php)

```php
use Core\Router;
use Controllers\PostController;

Router::get('/posts', [PostController::class, 'index']);
Router::post('/posts', [PostController::class, 'store']);
```

---

## 🌐 Licença

Este projeto é open-source sob a licença MIT.

---

Para dúvidas ou contribuições, abra uma *issue* ou envie sugestões diretamente ao repositório.
