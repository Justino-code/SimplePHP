# ⚙️ MicroFramework PHP

Um microframework PHP simples, modular e extensível — ideal para aplicações pequenas ou como base de aprendizado. Inspirado na arquitetura do Laravel, com foco em leveza, organização e produtividade.

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
- Servidor local (ex: Apache, Nginx ou `php -S`)

## 🚀 Instalação

```bash
git clone https://github.com/seu-usuario/microframework-php.git
cd microframework-php
composer install
cp .env.example .env
php -S localhost:8000 -t public
````

## 🧱 Estrutura Básica

```
src/
├── Core/          # Núcleo do framework
├── Http/          # Utilitários HTTP
├── Database/      # Conexão PDO
├── Controllers/   # Lógica de aplicação
├── Models/        # Acesso ao banco
├── helpers.php    # Funções globais
routes.php         # Arquivo de rotas
bootstrap.php      # Inicialização
.env               # Configurações locais
```

## 📚 Exemplo de Rota

```php
// routes.php
use Controllers\PostController;

Router::get('/posts', [PostController::class, 'index']);
Router::post('/posts', [PostController::class, 'store']);
```

## 📝 Licença

Código aberto sob licença [MIT](LICENSE).