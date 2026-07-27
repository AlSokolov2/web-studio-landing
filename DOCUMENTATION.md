# Техническая документация — тема Web Studio

## 1. Архитектура

Тема построена по модульному принципу. Каждый функциональный блок выделен в отдельный файл.

### 1.1. Точка входа

`functions.php` — центральный хаб темы. Подключает модули из `inc/`, регистрирует хуки `after_setup_theme` и `wp_enqueue_scripts`, содержит хелпер `web_studio_get_icon()`.

### 1.2. Жизненный цикл запроса

```
Запрос к корню сайта
  → WordPress определяет главную страницу (front-page.php)
    → get_header() → header.php → wp_head()
    → get_template_part('template-parts/section-hero')
    → get_template_part('template-parts/section-portfolio')
    → get_template_part('template-parts/section-about')
    → get_template_part('template-parts/section-contacts')
    → get_template_part('template-parts/section-feedback')
    → get_footer() → footer.php → wp_footer()
```

### 1.3. Классы в `inc/`

| Файл | Класс | Назначение |
|---|---|---|
| `cpt-portfolio.php` | — | Регистрация CPT `portfolio` и таксономии `portfolio_category` |
| `class-customizer.php` | `Web_Studio_Customizer` | Регистрация секций, настроек и контролов Кастомайзера |
| `class-ajax-handler.php` | `Web_Studio_Ajax_Handler` | Обработка AJAX-запросов формы обратной связи |

Все классы инициализируются в своих файлах (паттерн self-initialization), не требуя ручного вызова из `functions.php`.

---

## 2. Стандарты кодирования

Код следует [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/) за исключением:

- **Отступы**: 4 пробела вместо табов (требование проекта)

### 2.1. Ключевые правила

- `declare( strict_types=1 )` в начале каждого PHP-файла
- Длинный синтаксис массивов: `array( ... )`
- Пробелы внутри скобок функций: `wp_enqueue_style( 'handle', $src, ... )`
- `require_once` без скобок: `require_once $path;`
- Одинарные кавычки по умолчанию
- Yoda-условия для операторов сравнения
- Все строки переводимы через `__()`, `esc_html__()`, `esc_attr__()`
- Весь вывод экранирован: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`

---

## 3. Безопасность

### 3.1. Форма обратной связи

- **Nonce-проверка**: `check_ajax_referer( 'feedback_nonce', 'nonce', false )` — каждый AJAX-запрос проверяется на подлинность
- **Honeypot**: скрытое поле `website` — если заполнено, запрос отклоняется (боты заполняют все поля)
- **Санитизация ввода**: `sanitize_text_field()`, `sanitize_email()`, `sanitize_textarea_field()`, `wp_unslash()`
- **Серверная валидация**: после санитизации поля проверяются повторно (длина, формат email)
- **Отправка через `wp_mail()`**: без прямого вызова `mail()`, с заголовком `Content-Type: text/plain`

### 3.2. Вывод данных

- Все данные из Кастомайзера проходят через `esc_html()`, `esc_url()`, `wp_kses_post()`
- Изображения выводятся через `wp_get_attachment_image()` (встроенное экранирование)
- SVG-иконки захардкожены в `web_studio_get_icon()` — нет инъекции извне

### 3.3. База данных

- `WP_Query` для запросов портфолио — никакого прямого SQL
- `get_theme_mod()` для настроек — встроенное кеширование и санитизация WordPress

---

## 4. JavaScript

### 4.1. Общая архитектура

- **Без jQuery** — весь код на нативном JS (ES5 для совместимости)
- **Стратегия загрузки**: `defer` — скрипты не блокируют рендеринг
- **Разделение на два файла**:
  - `navigation.js` — мобильное меню (загружается на всех страницах)
  - `main.js` — форма, скролл, анимации (только на главной)

### 4.2. Передача данных из PHP в JS

```php
wp_add_inline_script( 'web-studio-main',
    'window.wsData=' . wp_json_encode( array( ... ) ) . ';',
    'before' );
```

Объект `wsData` доступен глобально до загрузки `main.js`.

### 4.3. AJAX-форма

```
Пользователь заполняет форму
  → blur/input: валидация отдельного поля
  → submit: валидация всех полей
    → fetch(POST) → admin-ajax.php?action=submit_feedback
      → Web_Studio_Ajax_Handler::handle()
        → проверка nonce → honeypot → санитизация → валидация → wp_mail()
        → wp_send_json_success() / wp_send_json_error()
    → обновление UI (статус, ошибки полей)
```

### 4.4. Анимации при скролле

`IntersectionObserver` отслеживает появление элементов в viewport и добавляет класс `animate--visible`. Элементы получают начальный класс `animate` (opacity: 0, translateY: 24px) и плавно проявляются.

---

## 5. CSS

### 5.1. Подход

- **Mobile-first**: базовые стили для мобильных устройств, медиа-запросы только для расширения
- **CSS Custom Properties**: все цвета, шрифты и отступы вынесены в `:root`
- **БЭМ-подобное именование**: `.block__element--modifier`
- **Без препроцессоров**: чистый CSS

### 5.2. Брейкпоинты

| Точка | Устройства |
|---|---|
| Базовый (0) | Телефоны (портрет) |
| ≥ 576px | Телефоны (ландшафт), маленькие планшеты |
| ≥ 768px | Планшеты, ноутбуки |
| ≥ 992px | Десктопы |
| ≥ 1200px | Большие экраны |

### 5.3. Кастомные свойства (полный список)

| Свойство | Назначение |
|---|---|
| `--color-primary` | Основной цвет (синий) |
| `--color-primary-dark` | Тёмный вариант основного |
| `--color-secondary` | Вторичный цвет (тёмный) |
| `--color-accent` | Акцентный (CTA, жёлтый) |
| `--color-bg` | Фон страницы |
| `--color-bg-alt` | Альтернативный фон секций |
| `--color-text` | Основной текст |
| `--color-text-light` | Приглушённый текст |
| `--color-border` | Границы |
| `--color-success` | Цвет успеха |
| `--color-error` | Цвет ошибки |
| `--font-primary` | Системный шрифт |
| `--spacing-xs` – `--spacing-xl` | Отступы (8px – 80px) |
| `--container-max` | Макс. ширина контейнера (1200px) |
| `--header-height` | Высота шапки (64px) |

---

## 6. Кастомайзер (Customizer API)

### 6.1. Панели и секции

Каждая секция лендинга имеет свою секцию в Кастомайзере:

- `web_studio_hero` — Hero (первый экран)
- `web_studio_portfolio` — Портфолио
- `web_studio_about` — О студии
- `web_studio_contacts` — Контакты
- `web_studio_feedback` — Форма обратной связи
- `web_studio_footer` — Подвал

### 6.2. Список настроек

| ID настройки | Тип | По умолчанию | Секция |
|---|---|---|---|
| `web_studio_hero_title` | text | «Создаём сайты, которые работают» | hero |
| `web_studio_hero_subtitle` | text | «Веб-студия полного цикла...» | hero |
| `web_studio_hero_cta_text` | text | «Обсудить проект» | hero |
| `web_studio_hero_cta_url` | url | `#feedback` | hero |
| `web_studio_hero_bg_image` | image | — | hero |
| `web_studio_portfolio_heading` | text | «Портфолио» | portfolio |
| `web_studio_about_heading` | text | «О студии» | about |
| `web_studio_about_content` | textarea | — | about |
| `web_studio_about_image` | image | — | about |
| `web_studio_contacts_heading` | text | «Контакты» | contacts |
| `web_studio_contacts_phone` | text | — | contacts |
| `web_studio_contacts_email` | email | `admin_email` | contacts |
| `web_studio_contacts_address` | text | — | contacts |
| `web_studio_form_heading` | text | «Обратная связь» | feedback |
| `web_studio_form_desc` | textarea | «Расскажите о вашем проекте...» | feedback |
| `web_studio_footer_copyright` | text | — | footer |

### 6.3. Добавление новых настроек

Для добавления новой настройки используйте метод `add_setting_and_control`:

```php
$this->add_setting_and_control(
    $wp_customize,
    'web_studio_new_setting',           // ID настройки
    array( 'default' => 'Значение' ),   // Аргументы настройки
    array(
        'label' => 'Моя настройка',      // Аргументы контрола
        'type'  => 'text',
    ),
    'web_studio_hero'                    // ID секции
);
```

---

## 7. Кастомный тип записей «Портфолио»

### 7.1. Основные параметры

- **Post Type**: `portfolio`
- **Таксономия**: `portfolio_category` (иерархическая)
- **Поддержка**: title, editor, thumbnail, excerpt
- **REST API**: включён (`show_in_rest => true`)
- **Slug**: `/portfolio/` для архива, `/portfolio/project-slug/` для записи

### 7.2. Как вывести больше проектов

В `template-parts/section-portfolio.php` измените параметр `posts_per_page`:

```php
$web_studio_portfolio_query = new WP_Query( array(
    'post_type'      => 'portfolio',
    'posts_per_page' => 9,   // Было 6
    ...
) );
```

---

## 8. Интернационализация (i18n)

- **Text Domain**: `web-studio`
- **Директория переводов**: `languages/`
- **Файл шаблона**: `languages/web-studio.pot`

Для генерации `.pot` файла используйте WP-CLI:

```bash
wp i18n make-pot . languages/web-studio.pot
```

---

## 9. SEO

- **title-tag**: WordPress управляет `<title>` (поддержка добавлена через `add_theme_support`)
- **Open Graph**: базовые теги в `header.php` (`og:title`, `og:description`, `og:type`, `og:url`)
- **Schema.org**: микроразметка `LocalBusiness` в секции контактов
- **Семантический HTML5**: `<header>`, `<main>`, `<footer>`, `<section>`, `<article>`, `<nav>`
- **Иерархия заголовков**: h1 в Hero, h2 в секциях, h3 в карточках портфолио

---

## 10. Доступность (a11y)

- **Skip-to-content**: ссылка в начале `<body>`, видимая при фокусе
- **ARIA-атрибуты**: `aria-label`, `aria-expanded`, `aria-controls`, `aria-required`, `aria-invalid`, `aria-live`, `aria-hidden`
- **Клавиатурная навигация**: `:focus-visible` вместо `:focus`, скрытие обводки только для нефокусированных элементов
- **Уменьшение движения**: `@media (prefers-reduced-motion: reduce)` отключает анимации
- **Контрастность**: цветовая схема соответствует WCAG AA

---

## 11. Производительность

- **Deferred JS**: скрипты загружаются с `strategy => 'defer'`
- **Lazy loading**: изображения портфолио с `loading="lazy"`
- **Системный шрифт**: без внешних запросов к Google Fonts
- **SVG-иконки**: встроенные (нет дополнительных HTTP-запросов)
- **Удалены лишние WP-хуки**: emoji, generator, wlwmanifest, RSD
- **Условная загрузка**: `main.js` только на главной странице

---

## 12. Расширение темы

### 12.1. Дочерняя тема

Тема полностью поддерживает дочерние темы. Пример `style.css` дочерней темы:

```css
/*
Theme Name: Web Studio Child
Template: web-studio
*/

:root {
    --color-primary: #dc2626; /* Красный вместо синего */
}
```

### 12.2. Хуки и фильтры

Тема использует стандартные хуки WordPress. Для точечной кастомизации можно использовать фильтры в `functions.php` дочерней темы или плагине.

---

## 13. Развёртывание

### 13.1. Docker (рекомендуется)

Проект использует Docker-контейнеры из `/home/alekzander/.wp-dev/`:

```bash
docker compose -f /path/to/.wp-dev/docker-compose.yml up -d
```

Тема монтируется как volume в `/var/www/html/wp-content/themes/web-studio`.

### 13.2. Production

1. Залейте папку темы на сервер в `/wp-content/themes/web-studio/`
2. Активируйте тему в админке
3. Настройте контент через Кастомайзер
4. Добавьте проекты в портфолио
5. Создайте меню: **Внешний вид → Меню → Создать меню** с якорными ссылками (`#hero`, `#portfolio`, `#about`, `#contacts`, `#feedback`)
