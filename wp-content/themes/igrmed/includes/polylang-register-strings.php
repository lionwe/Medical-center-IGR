<?php
/**
 * IGR Medical Translation System
 * Centralized string registration for Polylang plugin
 */

/**
 * Get all translatable strings
 */
function igrmed_get_translatable_strings(): array
{
    return [
        // HEADER
        'header_menu_directions' => 'Напрямки',
        'header_btn_text' => 'Записатися на прийом',
        'header_phone_label' => 'Телефон',
        'header_email_label' => 'Email',
        'header_address_label' => 'Адреса',
        'header_working_hours' => 'Графік роботи',
        
        // BUTTONS
        'btn_contact_us' => "Зв'язатися з нами",
        'btn_read_more' => 'Читати більше',
        'btn_submit' => 'Надіслати',
        'btn_book_appointment' => 'Записатися на прийом',
        'btn_consultation' => 'Консультація',
        'btn_details' => 'Детальніше',
        'btn_details_about_us' => 'Детальніше про нас',
        'btn_view_more_news' => 'Переглянути більше новин',
        
        // FORMS
        'form_contact_title' => 'Контактна форма',
        'form_first_name' => 'Ваше Ім\'я*',
        'form_last_name' => 'Ваше Прізвище*',
        'form_phone' => 'Телефон*',
        'form_email' => 'Email*',
        'form_message' => 'Повідомлення',
        'form_privacy' => 'Я погоджуюсь з політикою конфіденційності',
        'form_success_message' => 'Дякуємо! Ваше повідомлення надіслано.',
        'form_error_message' => 'Помилка! Будь ласка, спробуйте ще раз.',
        
        // FOOTER
        'footer_about_title' => 'Про клініку',
        'footer_services_title' => 'Послуги',
        'footer_contacts_title' => 'Зв\'язатися з нами',
        'footer_social_title' => 'Ми в соцмережах',
        'footer_copyright' => '© 2024 IGR Medical. Всі права захищені.',
        'footer_privacy_policy' => 'Політика конфіденційності',
        'footer_terms_of_use' => 'Умови використання',
        'footer_working_hours' => 'Графік роботи',
        
        // SOCIAL MEDIA
        'social_instagram' => 'Instagram',
        'social_facebook' => 'Facebook',
        'social_tiktok' => 'TikTok',
        'social_telegram' => 'Telegram',
        
        // BLOG
        'blog_title' => 'Блог',
        'blog_read_more' => 'Читати більше',
        'blog_reading_time' => '%d хв на прочитання',
        'blog_related_posts' => 'Схожі статті',
        'blog_categories' => 'Категорії',
        'blog_tags' => 'Теги',
        'blog_author' => 'Автор',
        'blog_date' => 'Дата',
        'blog_no_posts' => 'Статті не знайдено',
        'blog_load_more' => 'Завантажити ще',
        'blog_share_title' => 'Поширити',
        
        // SERVICES
        'services_title' => 'Послуги',
        'services_all' => 'Усі послуги',
        'services_details' => 'Детальніше',
        'services_price' => 'Ціна',
        'services_duration' => 'Тривалість',
        'services_doctor' => 'Лікар',
        'services_booking' => 'Записатися',
        
        // DOCTORS
        'doctors_title' => 'Наші лікарі',
        'doctors_specialization' => 'Спеціалізація',
        'doctors_experience' => 'Досвід',
        'doctors_education' => 'Освіта',
        'doctors_schedule' => 'Графік прийому',
        'doctors_book_appointment' => 'Записатися на прийом',
        'doctors_about' => 'Про лікаря',
        'doctors_certificates' => 'Сертифікати',
        'doctors_details' => 'Детальніше про лікаря',
        
        // SECTIONS
        'section_advantages' => 'Переваги',
        'section_when_visit' => 'Коли варто звернутися',
        'section_procedures' => 'Процедури та захворювання',
        
        // APPOINTMENTS
        'appointment_title' => 'Запис на консультацію',
        'appointment_select_service' => 'Оберіть послугу',
        'appointment_select_doctor' => 'Оберіть лікаря',
        'appointment_select_date' => 'Оберіть дату',
        'appointment_select_time' => 'Оберіть час',
        'appointment_confirm' => 'Підтвердити запис',
        'appointment_success' => 'Запис успішно створено!',
        'appointment_details' => 'Деталі запису',
        
        // REVIEWS
        'reviews_title' => 'Відгуки',
        'reviews_add' => 'Залишити відгук',
        'reviews_name' => 'Ваше ім\'я',
        'reviews_rating' => 'Оцінка',
        'reviews_comment' => 'Коментар',
        'reviews_submit' => 'Надіслати відгук',
        'reviews_thank_you' => 'Дякуємо за ваш відгук!',
        
        // CONTACTS
        'contacts_title' => 'Контакти',
        'contacts_address' => 'Адреса',
        'contacts_phone' => 'Телефон',
        'contacts_email' => 'Email',
        'contacts_map' => 'Карта',
        'contacts_form_title' => 'Зв\'язатися з нами',
        'contacts_working_hours' => 'Графік роботи',
        
        // PRICING
        'pricing_title' => 'Ціни',
        'pricing_service' => 'Послуга',
        'pricing_price' => 'Ціна',
        'pricing_duration' => 'Тривалість',
        'pricing_details' => 'Деталі',
        'pricing_book_now' => 'Записатися',
        'pricing_currency' => 'грн',
        
        // ERRORS
        'error_404_title' => 'Сторінку не знайдено',
        'error_404_text' => 'Вибачте, але сторінку, яку ви шукали, не існує.',
        'error_required' => 'Це поле є обов\'язковим',
        'error_email' => 'Введіть коректний email',
        'error_phone' => 'Введіть коректний номер телефону',
        'error_query_too_short' => 'Занадто короткий запит',
        'error_no_data' => 'Дані відсутні.',
        'diagnostics_all_procedures' => 'Всі процедури',
        
        // STATUS
        'loading' => 'Завантаження...',
        'success' => 'Успішно!',
        
        // BREADCRUMBS
        'breadcrumbs_home' => 'Головна',
        
        // SEARCH
        'search_placeholder' => 'Пошук',
        'search_no_results' => 'Нічого не знайдено',
        
        // PAGINATION
        'pagination_prev' => 'Попередня',
        'pagination_next' => 'Наступна',
    ];
}

/**
 * Register all strings with Polylang
 */
function igrmed_register_polylang_strings(): void
{
    if (!function_exists('pll_register_string')) {
        return;
    }
    
    $strings = igrmed_get_translatable_strings();
    
    foreach ($strings as $key => $value) {
        pll_register_string($key, $value, 'IGR Medical');
    }
}

// Hook to register strings
add_action('init', 'igrmed_register_polylang_strings');

/**
 * Get translated string by key
 */
function igrmed__(string $key): string
{
    if (!function_exists('pll__')) {
        $strings = igrmed_get_translatable_strings();
        return $strings[$key] ?? $key;
    }
    
    $strings = igrmed_get_translatable_strings();
    $original = $strings[$key] ?? $key;
    
    return pll__($original);
}

/**
 * Echo translated string by key
 */
function igrmed_e(string $key): void
{
    echo igrmed__($key);
}

/**
 * Get translated string with sprintf formatting
 */
function igrmed_sprintf(string $key, ...$args): string
{
    $string = igrmed__($key);
    return sprintf($string, ...$args);
}

/**
 * Dynamic form translation
 */
function igrmed_translate_form_output(string $html): string
{
    $replacements = [
        'Запис до лікаря' => igrmed__('appointment_title'),
        'Ваше Ім\'я*' => esc_attr(igrmed__('form_first_name')),
        'Ваше Прізвище*' => esc_attr(igrmed__('form_last_name')),
        'Телефон*' => esc_attr(igrmed__('form_phone')),
        'Email*' => esc_attr(igrmed__('form_email')),
        'Повідомлення' => esc_attr(igrmed__('form_message')),
        'Надіслати' => esc_attr(igrmed__('btn_submit')),
        'Зворотний зв\'язок' => igrmed__('form_contact_title'),
        "Зв'язатись з нами" => igrmed__('btn_contact_us'),
        'Консультація' => igrmed__('btn_consultation'),
        'Детальніше' => igrmed__('btn_details'),
        'Читати більше' => igrmed__('btn_read_more'),
        'Записатися на прийом' => igrmed__('btn_book_appointment'),
    ];
    
    return str_replace(array_keys($replacements), array_values($replacements), $html);
}

/**
 * Get current language code
 */
function igrmed_get_current_language(): string
{
    return function_exists('pll_current_language') ? pll_current_language() : '';
}
