<?php

use Src\Response;
use Src\Session;
use Src\RedirectResponse;

/**
 * Retorna uma instância de Response para respostas JSON ou plain text.
 */
if (!function_exists('response')) {
    function response(): Response {
        return new Response();
    }
}

/**
 * Redireciona para uma rota com parâmetros opcionais.
 */
if (!function_exists('redirect')) {
    function redirect(string $path, array $params = []): RedirectResponse {
        return new RedirectResponse($path, $params);
    }
}

/**
 * Verifica autenticação de forma simples com suporte a tipo de usuário.
 */
if (!function_exists('auth')) {
    function auth(): object {
        return new class {
            public function check(): bool {
                return isset($_SESSION['user_id']);
            }

            public function user(): ?object {
                return $this->check() ? (object)$_SESSION['user'] : null;
            }

            public function isAdmin(): bool {
                return $this->check() && ($_SESSION['user']['role'] ?? '') === 'admin';
            }
	    public function login(array $user): void {
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['user'] = $user;
            }

            public function logout(): void {
                unset($_SESSION['user_id'], $_SESSION['user']);
                session_destroy();
            }
	    public function isManager(): bool{
		return $this->check() && ($_SESSION['user']['role'] ?? '') === 'gestor';
	    }
        };
    }
}

/**
 * Gera ou retorna o token CSRF da sessão.
 */
if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

/**
 * Verifica se o CSRF token enviado via POST corresponde ao da sessão.
 */
if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token(): bool {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_token'] ?? '';
            if (!$token || $token !== ($_SESSION['csrf_token'] ?? '')) {
                http_response_code(403);
                echo "CSRF token inválido ou ausente!";
                exit;
            }
            return true;
        }
        return false;
    }
}

if (!function_exists('view')) {
    /**
     * Renderiza uma view e aplica um layout, passando variáveis automaticamente.
     */
    function view(string $template, array $data = [], string $layout = 'layouts.app')
    {
        // Protege contra LFI (Local File Inclusion)
        //$template = preg_replace('/[^a-zA-Z0-9\/_-]/', '', $template);
        //$layout = preg_replace('/[^a-zA-Z0-9\/_-]/', '', $layout);

	//dd($template);

        // Converte o ponto para barra (ex: 'home.contact' -> 'home/contact')
        $templatePath = __DIR__ ."/../app/views/" . str_replace('.', '/', $template) . '.php';
        $layoutPath = __DIR__ . "/../app/views/" . str_replace('.', '/', $layout) . '.php';
	//dd($templatePath);

        // Verifica se a view existe
        if (!file_exists($templatePath)) {
            trigger_error("Template não encontrado: {$template}", E_USER_ERROR);
            return '';
        }

        // Inclui o token CSRF como parte dos dados da view
        $data['csrf_token'] = csrf_token();

        // Extrai os dados para variáveis disponíveis na view
        extract($data, EXTR_SKIP);

        // Inicia o buffer de saída
        ob_start();
        require $templatePath;
        $content = ob_get_clean();

        // Verifica se um layout foi definido e se ele existe
        if ($layout && file_exists($layoutPath)) {
            ob_start();
            require $layoutPath;
	    $layout_template = ob_get_clean();
	    echo $layout_template;
            //return ob_get_clean();
        }

        // Retorna o conteúdo da view sem layout, se não houver layout
	//echo $content;
        return $content;
    }
}


/**
 * Carrega um componente (parcial) da pasta de views/components.
 */
if (!function_exists('component')) {
    function component(string $name, array $data = []): string {
        $name = preg_replace('/[^a-zA-Z0-9\/_-]/', '', $name);
        $componentPath = __DIR__ . "/../views/components/{$name}.php";

        if (!file_exists($componentPath)) {
            return '';
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $componentPath;
        return ob_get_clean();
    }
}

/**
 * Dump and Die - imprime variáveis com formatação e encerra o script.
 */
if (!function_exists('dd')) {
    function dd(...$vars): void {
        echo '<pre style="background:#222;color:#0f0;padding:10px;border-radius:5px;font-family:monospace;">';
        foreach ($vars as $var) {
            var_dump($var);
            echo "\n";
        }
        echo '</pre>';
        exit(1);
    }
}

if (!function_exists('session')) {
    /**
     * Retorna uma instância única da classe Session.
     *
     * @return \Src\Session
     */
    function session(): Session
    {
        static $instance = null;

        // Cria a instância apenas uma vez (singleton)
        if ($instance === null) {
            $instance = new Session();
        }

        return $instance;
    }
}

if (!function_exists('safe_html')) {
    /**
     * Garante que o argumento é string antes de usar htmlspecialchars()
     *
     * @return \Src\Session
     */
   function safe_html($input) {
    if (is_array($input)) {
        // Se for array, converte cada item e junta com quebra de linha
        return implode('<br>', array_map('htmlspecialchars', $input));
    }
    // Senão, converte direto
    return htmlspecialchars($input ?? '');
}

function csrf_field(): string {
    return '<input type="hidden" name="_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

}

if (!function_exists('old')) {
    function old(string $key, $default = '')
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        $value = $_SESSION['old'][$key] ?? $default;

        return htmlspecialchars((string) $value);
    }
}

function renderPaginationCss(int $currentPage, int $lastPage, string $baseUrl = '?pagina='): string
{
    if ($lastPage <= 1) return ''; // Não exibe paginação se só tem 1 página

    $html = '<nav class="pagination"><ul style="display:flex;gap:5px;list-style:none;">';

    // Página anterior
    if ($currentPage > 1) {
        $html .= '<li><a href="' . $baseUrl . ($currentPage - 1) . '">&laquo; Anterior</a></li>';
    }

    // Páginas numéricas
    for ($i = 1; $i <= $lastPage; $i++) {
        if ($i == $currentPage) {
            $html .= '<li><strong>' . $i . '</strong></li>';
        } else {
            $html .= '<li><a href="' . $baseUrl . $i . '">' . $i . '</a></li>';
        }
    }

    // Próxima página
    if ($currentPage < $lastPage) {
        $html .= '<li><a href="' . $baseUrl . ($currentPage + 1) . '">Próxima &raquo;</a></li>';
    }

    $html .= '</ul></nav>';
    return $html;
}

function renderPagination(int $currentPage, int $totalPages, string $baseUrl = '?pagina='): string
{
    if ($totalPages <= 1) return '';

    $html = '<nav aria-label="Paginação"><ul class="pagination justify-content-center">';

    // Botão anterior
    if ($currentPage > 1) {
        $html .= '<li class="page-item">
            <a class="page-link" href="' . $baseUrl . ($currentPage - 1) . '" aria-label="Anterior">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
    }

    // Páginas numeradas (com limite visual, ex: 5 páginas)
    $range = 2; // número de páginas antes/depois da atual
    $start = max(1, $currentPage - $range);
    $end = min($totalPages, $currentPage + $range);

    if ($start > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . '1">1</a></li>';
        if ($start > 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }

    for ($i = $start; $i <= $end; $i++) {
        $active = $i === $currentPage ? ' active' : '';
        $html .= '<li class="page-item' . $active . '">
            <a class="page-link" href="' . $baseUrl . $i . '">' . $i . '</a>
        </li>';
    }

    if ($end < $totalPages) {
        if ($end < $totalPages - 1) {
            $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
        $html .= '<li class="page-item"><a class="page-link" href="' . $baseUrl . $totalPages . '">' . $totalPages . '</a></li>';
    }

    // Botão próximo
    if ($currentPage < $totalPages) {
        $html .= '<li class="page-item">
            <a class="page-link" href="' . $baseUrl . ($currentPage + 1) . '" aria-label="Próximo">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
    }

    $html .= '</ul></nav>';
    return $html;
}




