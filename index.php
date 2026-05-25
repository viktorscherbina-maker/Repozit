<?php
$errors = [];
$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';

function field(string $name, string $default = ''): string
{
    return htmlspecialchars(trim($_POST[$name] ?? $default), ENT_QUOTES, 'UTF-8');
}

$application = [
    'name' => field('name'),
    'email' => field('email'),
    'phone' => field('phone'),
    'company' => field('company'),
    'role' => field('role'),
    'comment' => field('comment'),
];

if ($submitted) {
    if ($application['name'] === '') {
        $errors[] = 'Укажите имя заявителя.';
    }

    if (!filter_var($application['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Укажите корректный email.';
    }

    if ($application['phone'] === '') {
        $errors[] = 'Укажите номер телефона.';
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Заявка на регистрацию</title>
    <style>
        :root {
            --bg: #eef4ff;
            --card: #ffffff;
            --ink: #172033;
            --muted: #697386;
            --brand: #3264ff;
            --brand-dark: #214bd1;
            --success: #0f8f61;
            --danger: #c83d3d;
            --line: #dfe7f6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(50, 100, 255, 0.22), transparent 30rem),
                linear-gradient(135deg, #f7fbff 0%, var(--bg) 100%);
        }

        .page {
            display: grid;
            grid-template-columns: minmax(18rem, 0.9fr) minmax(20rem, 1.1fr);
            gap: 2rem;
            width: min(1120px, calc(100% - 2rem));
            margin: 0 auto;
            padding: 4rem 0;
            align-items: center;
        }

        .hero {
            padding: 2rem;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding: 0.45rem 0.8rem;
            color: var(--brand-dark);
            background: rgba(50, 100, 255, 0.12);
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 700;
        }

        h1 {
            margin: 0 0 1rem;
            font-size: clamp(2.2rem, 5vw, 4.2rem);
            line-height: 1.04;
            letter-spacing: -0.04em;
        }

        .lead {
            margin: 0 0 2rem;
            color: var(--muted);
            font-size: 1.1rem;
            line-height: 1.7;
        }

        .benefits {
            display: grid;
            gap: 0.9rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .benefits li {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            color: #354056;
            font-weight: 700;
        }

        .check {
            display: grid;
            place-items: center;
            flex: 0 0 1.7rem;
            width: 1.7rem;
            height: 1.7rem;
            color: #ffffff;
            background: var(--success);
            border-radius: 50%;
            font-size: 0.9rem;
        }

        .card {
            padding: clamp(1.25rem, 4vw, 2.25rem);
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.75);
            border-radius: 2rem;
            box-shadow: 0 1.25rem 4rem rgba(39, 70, 129, 0.18);
            backdrop-filter: blur(18px);
        }

        .card h2 {
            margin: 0 0 0.45rem;
            font-size: 1.75rem;
        }

        .hint {
            margin: 0 0 1.5rem;
            color: var(--muted);
        }

        .alert {
            margin-bottom: 1.25rem;
            padding: 1rem;
            border-radius: 1rem;
            line-height: 1.5;
        }

        .alert-success {
            color: #07573c;
            background: #ddf8ee;
            border: 1px solid #a8ead2;
        }

        .alert-error {
            color: #842121;
            background: #ffe9e9;
            border: 1px solid #ffc3c3;
        }

        .alert ul {
            margin: 0.5rem 0 0;
            padding-left: 1.2rem;
        }

        form {
            display: grid;
            gap: 1rem;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        label {
            display: grid;
            gap: 0.45rem;
            color: #2d374d;
            font-size: 0.95rem;
            font-weight: 700;
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 0.9rem;
            padding: 0.9rem 1rem;
            color: var(--ink);
            background: #fbfdff;
            font: inherit;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--brand);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(50, 100, 255, 0.13);
        }

        textarea {
            min-height: 7rem;
            resize: vertical;
        }

        .full {
            grid-column: 1 / -1;
        }

        .consent {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            color: var(--muted);
            font-size: 0.9rem;
            font-weight: 400;
        }

        .consent input {
            width: auto;
            margin-top: 0.15rem;
        }

        button,
        .secondary-action {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border: 0;
            border-radius: 1rem;
            padding: 1rem 1.2rem;
            color: #ffffff;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            font: inherit;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 0.9rem 1.8rem rgba(50, 100, 255, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        button:hover,
        .secondary-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 1.1rem 2rem rgba(50, 100, 255, 0.3);
        }

        @media (max-width: 840px) {
            .page {
                grid-template-columns: 1fr;
                padding: 2rem 0;
            }

            .hero {
                padding: 1rem 0 0;
            }
        }

        @media (max-width: 560px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="hero" aria-labelledby="page-title">
            <div class="eyebrow">Онлайн-заявка</div>
            <h1 id="page-title">Регистрация нового участника</h1>
            <p class="lead">
                Заполните короткую форму, и менеджер проверит данные, подготовит доступ
                и свяжется с вами для подтверждения регистрации.
            </p>
            <ul class="benefits">
                <li><span class="check">✓</span> Ответ по заявке в течение рабочего дня</li>
                <li><span class="check">✓</span> Защищенная передача контактных данных</li>
                <li><span class="check">✓</span> Подходит для клиентов, партнеров и сотрудников</li>
            </ul>
        </section>

        <section class="card" aria-labelledby="form-title">
            <h2 id="form-title">Заявка на регистрацию</h2>
            <p class="hint">Поля с контактами обязательны для быстрой обратной связи.</p>

            <?php if ($submitted && !$errors): ?>
                <div class="alert alert-success" role="status">
                    <strong>Заявка принята.</strong><br>
                    Спасибо, <?= $application['name']; ?>! Мы отправим подтверждение на <?= $application['email']; ?>.
                </div>
            <?php elseif ($errors): ?>
                <div class="alert alert-error" role="alert">
                    <strong>Проверьте форму:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!$submitted || $errors): ?>
                <form method="post" action="">
                    <div class="grid">
                        <label>
                            Имя и фамилия
                            <input type="text" name="name" placeholder="Анна Иванова" value="<?= $application['name']; ?>" required>
                        </label>

                        <label>
                            Email
                            <input type="email" name="email" placeholder="anna@example.com" value="<?= $application['email']; ?>" required>
                        </label>

                        <label>
                            Телефон
                            <input type="tel" name="phone" placeholder="+7 900 000-00-00" value="<?= $application['phone']; ?>" required>
                        </label>

                        <label>
                            Организация
                            <input type="text" name="company" placeholder="ООО «Пример»" value="<?= $application['company']; ?>">
                        </label>

                        <label class="full">
                            Тип регистрации
                            <select name="role">
                                <option value="client" <?= $application['role'] === 'client' ? 'selected' : ''; ?>>Клиент</option>
                                <option value="partner" <?= $application['role'] === 'partner' ? 'selected' : ''; ?>>Партнер</option>
                                <option value="employee" <?= $application['role'] === 'employee' ? 'selected' : ''; ?>>Сотрудник</option>
                            </select>
                        </label>

                        <label class="full">
                            Комментарий
                            <textarea name="comment" placeholder="Расскажите, какой доступ нужно подготовить"><?= $application['comment']; ?></textarea>
                        </label>
                    </div>

                    <label class="consent">
                        <input type="checkbox" name="consent" required>
                        <span>Я согласен на обработку персональных данных для рассмотрения заявки.</span>
                    </label>

                    <button type="submit">Отправить заявку</button>
                </form>
            <?php else: ?>
                <a class="secondary-action" href="/">Отправить новую заявку</a>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
