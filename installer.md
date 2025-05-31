# 🛠️ Instalador de Banco de Dados (SimplePHP)

Este módulo faz parte do Microframework SimplePHP e tem como objetivo facilitar a **instalação automática do banco de dados**, incluindo:

- Criação do banco de dados caso não exista
- Execução de um script SQL contendo as tabelas iniciais
- Inserção de um usuário admin padrão

---

## ⚙️ Pré-requisitos

Certifique-se de ter:

- O arquivo `.env` configurado com as credenciais do banco:
  ```env
  DB_HOST=localhost
  DB_NAME=tech_db
  DB_USER=root
  DB_PASS=
  DB_CHARSET=utf8mb4
````

* Um arquivo SQL com as instruções para criar as tabelas (exemplo: `sql/schema.sql`)

---

## 🚀 Como usar

### 1. Instalação via terminal

Execute o seguinte comando:

```bash
php installer
```

Esse comando:

1. Cria o banco de dados (`CREATE DATABASE IF NOT EXISTS`)
2. Executa as tabelas definidas no `sql/schema.sql`
3. Insere um usuário admin padrão, caso ainda não exista

---

## 🧪 Modo avançado: passar parâmetros

Você também pode passar o parâmetro `--admin` para recriar o admin padrão:

```bash
php installer --admin
```

Isso forçará a verificação e tentativa de inserção do admin novamente.

---

## 👤 Admin padrão

As credenciais do administrador padrão criadas automaticamente são:

* **Email:** `admin@email.com`
* **Senha:** `admin123`

⚠️ **Recomendamos alterar esses dados após o primeiro login.**

---

## 🧱 Estrutura esperada do projeto

```
my-project/
├── sql/
│   └── schema.sql         # Script com as instruções SQL
├── src/
│   └── Database/
│       └── Deprecated/
│           └── CreateDatabaseAndTables.php
├── installer.php          # Arquivo responsável por executar a instalação
└── .env                   # Arquivo com dados de conexão
```

---

## 🛑 Importante

Este recurso foi criado com foco em **prototipagem e testes locais**. **Não recomendamos o uso em produção**, especialmente em ambientes onde a execução de scripts pode comprometer a segurança ou integridade do banco de dados.

---

## 🤝 Contribuindo

Sinta-se à vontade para abrir issues, enviar pull requests ou sugerir melhorias. Estamos construindo algo simples, modular e educativo — sua colaboração é bem-vinda!

---

## 📄 Licença

Distribuído sob a licença [MIT](../../LICENSE).