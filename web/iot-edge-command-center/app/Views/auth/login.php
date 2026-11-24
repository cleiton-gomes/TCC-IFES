
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0b0f19">

    <title>IoT-Edge Command Center - Login</title>

    <!-- Bootstrap 5 CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <style>
        :root {
            color-scheme: dark;
            --login-bg: #0b0f19;
            --login-panel: rgba(17, 24, 39, 0.92);
            --login-text: #f8fafc;
            --login-muted: #94a3b8;
            --login-blue: #3b82f6;
            --login-border: rgba(255, 255, 255, 0.12);
        }

        * { box-sizing: border-box; }

        body {
            background:
                radial-gradient(circle at 12% 15%, rgba(37, 99, 235, .16), transparent 32%),
                radial-gradient(circle at top right, #1e293b, #0f172a 52%, var(--login-bg));
            color: var(--login-text);
            font-family: 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            padding: 2.35rem;
            background: var(--login-panel);
            border: 1px solid var(--login-border);
            border-radius: 20px;
            box-shadow: 0 22px 60px rgba(0, 0, 0, .45);
            backdrop-filter: blur(16px);
        }

        .brand-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.35rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 17px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            box-shadow: 0 0 24px rgba(59, 130, 246, .32);
        }

        .brand-caption {
            color: var(--login-muted);
            font-size: .86rem;
            line-height: 1.5;
        }

        .demo-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            text-align: left;
            color: #cbd5e1;
            background: rgba(245, 158, 11, .075);
            border: 1px solid rgba(245, 158, 11, .22);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: .76rem;
            line-height: 1.45;
        }

        .form-label {
            margin-bottom: .48rem;
            color: #e2e8f0;
            font-size: .82rem;
            font-weight: 650;
        }

        .input-group-text {
            min-width: 46px;
            justify-content: center;
            background: #101a2b;
            color: var(--login-muted);
            border-color: rgba(255, 255, 255, .14);
        }

        .form-control-dark,
        .form-select-dark {
            min-height: 46px;
            background-color: rgba(30, 41, 59, .72);
            border: 1px solid rgba(255, 255, 255, .15);
            color: #fff;
            border-radius: 10px;
        }

        .input-group > .form-control-dark,
        .input-group > .form-select-dark {
            border-radius: 0 10px 10px 0;
        }

        .form-control-dark:focus,
        .form-select-dark:focus {
            background-color: rgba(30, 41, 59, .96);
            border-color: var(--login-blue);
            color: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .2);
        }

        .form-control-dark::placeholder { color: #8392a8; }
        .form-select-dark option { background: #111827; color: #fff; }

        .form-control-dark:-webkit-autofill,
        .form-control-dark:-webkit-autofill:hover,
        .form-control-dark:-webkit-autofill:focus {
            -webkit-text-fill-color: #fff;
            transition: background-color 9999s ease-in-out 0s;
        }

        .password-toggle {
            min-width: 46px;
            background: #101a2b;
            color: var(--login-muted);
            border: 1px solid rgba(255, 255, 255, .14);
            border-left: 0;
            border-radius: 0 10px 10px 0;
        }

        .password-toggle:hover,
        .password-toggle:focus-visible {
            color: #fff;
            background: #1e293b;
        }

        .btn-primary-neon {
            min-height: 47px;
            padding: .78rem 1rem;
            border: 0;
            border-radius: 10px;
            color: #fff;
            font-weight: 700;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 4px 15px rgba(37, 99, 235, .3);
            transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .btn-primary-neon:hover {
            color: #fff;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            transform: translateY(-1px);
            box-shadow: 0 7px 20px rgba(59, 130, 246, .36);
        }

        .btn-primary-neon:focus-visible,
        .btn-register:focus-visible {
            outline: 2px solid #93c5fd;
            outline-offset: 3px;
        }

        .btn-register {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: .76rem 1rem;
            border: 1px solid rgba(59, 130, 246, .48);
            border-radius: 10px;
            color: #bfdbfe;
            background: rgba(37, 99, 235, .07);
            font-weight: 650;
            text-decoration: none;
            transition: background .2s ease, border-color .2s ease;
        }

        .btn-register:hover {
            color: #fff;
            background: rgba(37, 99, 235, .16);
            border-color: rgba(96, 165, 250, .8);
        }

        .form-hint {
            margin-top: 5px;
            color: #94a3b8;
            font-size: .72rem;
            line-height: 1.45;
        }

        .login-footer { gap: 12px; }
        .hover-white { transition: color .2s ease; }
        .hover-white:hover { color: #fff !important; }

        .form-check-input {
            background-color: #1e293b;
            border-color: rgba(255, 255, 255, .25);
        }

        .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .form-check-label {
            cursor: pointer;
        }

        /* Modal de cadastro */
        .modal-content-dark {
            color: #f8fafc;
            background: #111827;
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 16px;
            box-shadow: 0 24px 70px rgba(0, 0, 0, .55);
        }

        .modal-content-dark .modal-header,
        .modal-content-dark .modal-footer {
            border-color: rgba(255, 255, 255, .1);
        }

        .modal-content-dark .btn-close {
            filter: invert(1) grayscale(100%);
        }

        .profile-info {
            padding: 10px 12px;
            border-radius: 9px;
            background: rgba(56, 189, 248, .07);
            border: 1px solid rgba(56, 189, 248, .19);
            color: #cbd5e1;
            font-size: .76rem;
            line-height: 1.5;
        }

        .modal-backdrop.show { opacity: .75; }

        @media (max-width: 480px) {
            body { padding: 14px; }
            .login-card { padding: 1.65rem 1.2rem; border-radius: 16px; }
            .login-footer { align-items: flex-start !important; flex-direction: column; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body>
    <main class="login-card text-center">
        <div class="brand-icon" aria-hidden="true">
            <i class="fa-solid fa-shield-halved fa-2x text-white"></i>
        </div>

        <h1 class="h3 fw-bold text-white mb-1">Central IoT-Edge</h1>
        <p class="brand-caption mb-4">Inspeção Móvel &amp; Monitoramento Inteligente</p>

        <div class="demo-note mb-4" role="note">
            <i class="fa-solid fa-shield-halved text-info mt-1" aria-hidden="true"></i>
            <div>
                <strong>Acesso protegido.</strong>
                Entre com suas credenciais cadastradas para acessar o IoT-Edge Command Center.
            </div>
        </div>

        <!-- Formulário de autenticação processado pelo CodeIgniter Shield -->
        <form
            id="loginForm"
            action="<?= url_to('login') ?>"
            method="post"
            autocomplete="on"
        >
            <?= csrf_field() ?>

            <!-- Mensagens de autenticação e validação -->
            <?php if (session('error') !== null): ?>
                <div class="alert alert-danger py-2 small text-start" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-1" aria-hidden="true"></i>
                    <?= esc(session('error')) ?>
                </div>
            <?php elseif (session('errors') !== null): ?>
                <div class="alert alert-danger py-2 small text-start" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-1" aria-hidden="true"></i>

                    <?php if (is_array(session('errors'))): ?>
                        <?php foreach (session('errors') as $error): ?>
                            <div><?= esc($error) ?></div>
                        <?php endforeach ?>
                    <?php else: ?>
                        <?= esc(session('errors')) ?>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <?php if (session('message') !== null): ?>
                <div class="alert alert-success py-2 small text-start" role="status">
                    <?= esc(session('message')) ?>
                </div>
            <?php endif ?>

            <div class="mb-3 text-start">
                <label for="email" class="form-label">E-mail</label>
                <div class="input-group">
                    <span class="input-group-text" aria-hidden="true">
                        <i class="fa-solid fa-envelope"></i>
                    </span>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control form-control-dark"
                        placeholder="voce@exemplo.com"
                        autocomplete="username"
                        maxlength="190"
                        value="<?= esc(old('email') ?? '', 'attr') ?>"
                        required
                    >
                </div>
            </div>

            <div class="mb-3 text-start">
                <label for="password" class="form-label">Senha de acesso</label>
                <div class="input-group">
                    <span class="input-group-text" aria-hidden="true">
                        <i class="fa-solid fa-lock"></i>
                    </span>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control form-control-dark"
                        placeholder="Digite sua senha"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        type="button"
                        class="btn password-toggle"
                        id="toggleLoginPassword"
                        aria-label="Mostrar senha"
                        aria-pressed="false"
                    >
                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <!-- Opção de manter a sessão -->
            <div class="form-check text-start mb-4">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="remember"
                    id="remember"
                    value="1"
                    <?= old('remember') ? 'checked' : '' ?>
                >

                <label class="form-check-label text-secondary small" for="remember">
                    Manter minha sessão iniciada
                </label>
            </div>

            <button type="submit" class="btn btn-primary-neon w-100 mb-3">
                <i class="fa-solid fa-right-to-bracket me-2" aria-hidden="true"></i>
                Acessar Painel Tático
            </button>
        </form>

        <div class="pt-3 mt-3 border-top border-secondary border-opacity-25">
            <p class="text-secondary small mb-2">Ainda não possui acesso ao sistema?</p>

            <button
                type="button"
                class="btn-register"
                data-bs-toggle="modal"
                data-bs-target="#registerModal"
            >
                <i class="fa-solid fa-user-plus" aria-hidden="true"></i>
                Solicitar acesso / Cadastrar usuário
            </button>

            <p class="form-hint text-center mb-0 mt-2">
                O perfil definitivo é atribuído após a aprovação do administrador.
            </p>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-secondary border-opacity-25 login-footer">
            <span class="badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25">
                v2.4 IoT-Edge
            </span>

            <a
                href="<?= esc(site_url('videomonitoramento')) ?>"
                class="text-secondary text-decoration-none small hover-white"
                title="Abrir a tela demonstrativa"
            >
                Abrir simulação
                <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i>
            </a>
        </div>
    </main>

    <!-- Modal de solicitação de cadastro: ainda demonstrativo -->
    <div
        class="modal fade"
        id="registerModal"
        tabindex="-1"
        aria-labelledby="registerModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content modal-content-dark">
                <div class="modal-header px-4 pt-4">
                    <div>
                        <h2 class="modal-title h5 fw-bold" id="registerModalLabel">
                            <i class="fa-solid fa-user-plus text-info me-2" aria-hidden="true"></i>
                            Solicitar acesso
                        </h2>

                        <p class="text-secondary small mb-0 mt-1">
                            Preencha seus dados para solicitar uma conta.
                        </p>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Fechar"
                    ></button>
                </div>

                <div class="modal-body px-4 py-3">
                    <div class="demo-note mb-3">
                        <i class="fa-solid fa-circle-info text-warning mt-1" aria-hidden="true"></i>

                        <div>
                            Este formulário ainda não grava os dados.
                            A persistência e a aprovação serão implementadas na etapa de back-end.
                        </div>
                    </div>

                    <form id="registerForm" onsubmit="handleRegister(event)" novalidate>
                        <div class="mb-3">
                            <label for="registerName" class="form-label">Nome completo</label>

                            <input
                                type="text"
                                id="registerName"
                                name="nome"
                                class="form-control form-control-dark"
                                placeholder="Digite seu nome completo"
                                autocomplete="name"
                                minlength="3"
                                maxlength="120"
                                required
                            >

                            <div class="invalid-feedback">
                                Informe seu nome (mínimo de 3 caracteres).
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="registerEmail" class="form-label">E-mail</label>

                            <input
                                type="email"
                                id="registerEmail"
                                name="email"
                                class="form-control form-control-dark"
                                placeholder="voce@exemplo.com"
                                autocomplete="email"
                                maxlength="190"
                                required
                            >

                            <div class="invalid-feedback">
                                Informe um endereço de e-mail válido.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Criar senha</label>

                            <div class="input-group">
                                <input
                                    type="password"
                                    id="registerPassword"
                                    name="senha"
                                    class="form-control form-control-dark"
                                    placeholder="Crie uma senha (mínimo de 8 caracteres)"
                                    autocomplete="new-password"
                                    minlength="8"
                                    required
                                >

                                <button
                                    type="button"
                                    class="btn password-toggle"
                                    data-toggle-password="registerPassword"
                                    aria-label="Mostrar senha"
                                    aria-pressed="false"
                                >
                                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>

                            <div class="invalid-feedback">
                                A senha deve ter pelo menos 8 caracteres.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="registerPasswordConfirm" class="form-label">
                                Confirmar senha
                            </label>

                            <input
                                type="password"
                                id="registerPasswordConfirm"
                                name="confirmar_senha"
                                class="form-control form-control-dark"
                                placeholder="Digite a senha novamente"
                                autocomplete="new-password"
                                minlength="8"
                                required
                            >

                            <div class="invalid-feedback" id="confirmPasswordFeedback">
                                As senhas devem ser iguais.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="requestedProfile" class="form-label">
                                Perfil solicitado
                            </label>

                            <select
                                id="requestedProfile"
                                name="perfil_solicitado"
                                class="form-select form-select-dark"
                                required
                            >
                                <option value="" selected disabled>
                                    Selecione o perfil desejado
                                </option>

                                <option value="visualizador">
                                    Visualizador — consulta dashboards, leituras e eventos
                                </option>

                                <option value="operador">
                                    Operador — monitoramento e operações autorizadas
                                </option>
                            </select>

                            <div class="invalid-feedback">
                                Selecione o perfil solicitado.
                            </div>

                            <div class="form-hint">
                                O perfil informado é apenas uma solicitação.
                                O administrador fará a atribuição definitiva.
                                O perfil Administrador não pode ser solicitado por este formulário.
                            </div>
                        </div>

                        <div class="profile-info mb-3">
                            <i class="fa-solid fa-shield-halved text-info me-1" aria-hidden="true"></i>

                            Para proteger o sistema, os privilégios são concedidos pelo administrador.
                            Enviar esta solicitação não cria uma conta nem libera o acesso nesta versão demonstrativa.
                        </div>

                        <div
                            id="registerMessage"
                            class="alert py-2 small d-none"
                            role="status"
                            aria-live="polite"
                        ></div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary-neon">
                                <i class="fa-solid fa-paper-plane me-2" aria-hidden="true"></i>
                                Enviar solicitação demonstrativa
                            </button>

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal"
                            >
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        'use strict';

        /*
         * O formulário principal de login não utiliza JavaScript
         * para autenticar ou redirecionar o usuário.
         *
         * A autenticação é processada pelo CodeIgniter Shield.
         */

        function handleRegister(event) {
            event.preventDefault();

            const form = event.currentTarget;
            const name = document.getElementById('registerName');
            const email = document.getElementById('registerEmail');
            const password = document.getElementById('registerPassword');
            const confirmPassword = document.getElementById('registerPasswordConfirm');
            const profile = document.getElementById('requestedProfile');
            const feedback = document.getElementById('confirmPasswordFeedback');
            const message = document.getElementById('registerMessage');

            message.className = 'alert py-2 small d-none';
            message.textContent = '';

            [name, email, password, confirmPassword, profile].forEach((field) => {
                field.classList.remove('is-invalid');
            });

            let valid = true;

            if (name.value.trim().length < 3) {
                name.classList.add('is-invalid');
                valid = false;
            }

            if (!email.checkValidity() || !email.value.trim()) {
                email.classList.add('is-invalid');
                valid = false;
            }

            if (password.value.length < 8) {
                password.classList.add('is-invalid');
                valid = false;
            }

            if (confirmPassword.value.length < 8 || confirmPassword.value !== password.value) {
                confirmPassword.classList.add('is-invalid');

                feedback.textContent = confirmPassword.value !== password.value
                    ? 'As senhas informadas não são iguais.'
                    : 'Confirme a senha com pelo menos 8 caracteres.';

                valid = false;
            }

            if (!profile.value) {
                profile.classList.add('is-invalid');
                valid = false;
            }

            if (!valid) {
                message.textContent = 'Revise os campos destacados e tente novamente.';
                message.classList.add('alert-danger');
                message.classList.remove('d-none');
                return;
            }

            /*
             * Cadastro ainda demonstrativo.
             * Nenhum dado é enviado ou persistido.
             */
            message.textContent =
                'Demonstração concluída: os dados foram validados apenas no navegador, mas nenhum cadastro foi gravado. Na etapa de back-end, a solicitação será registrada e dependerá de aprovação.';

            message.classList.add('alert-info');
            message.classList.remove('d-none');
        }

        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);

            if (!input) return;

            const isShowing = input.type === 'text';

            input.type = isShowing ? 'password' : 'text';

            button.setAttribute('aria-pressed', String(!isShowing));
            button.setAttribute(
                'aria-label',
                isShowing ? 'Mostrar senha' : 'Ocultar senha'
            );

            const icon = button.querySelector('i');

            if (icon) {
                icon.classList.toggle('fa-eye', isShowing);
                icon.classList.toggle('fa-eye-slash', !isShowing);
            }
        }

        document.getElementById('toggleLoginPassword').addEventListener('click', function () {
            togglePassword('password', this);
        });

        document.querySelectorAll('[data-toggle-password]').forEach((button) => {
            button.addEventListener('click', function () {
                togglePassword(
                    this.getAttribute('data-toggle-password'),
                    this
                );
            });
        });
    </script>
</body>
</html>
