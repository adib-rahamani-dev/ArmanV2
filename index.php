<?php
declare(strict_types=1);

require __DIR__ . '/config/config.php';
$site = require __DIR__ . '/data/site.php';
$routes = require __DIR__ . '/data/routes.php';
$home = $routes['home'];
$seo = [
    'title' => $home['title'],
    'description' => $home['description'],
    'canonical' => absolute_url(),
    'type' => 'website',
    'schema' => [
        [
            '@type' => 'ProfessionalService',
            '@id' => site_base_url() . '/#business',
            'name' => 'REDT | گروه قرمز',
            'url' => absolute_url(),
            'image' => absolute_url('assets/images/hero/hero-redt.jpg'),
            'priceRange' => '$$$',
            'telephone' => '+98-21-8876-5432',
            'email' => 'hello@redt.ir',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'تهران',
                'addressCountry' => 'IR',
            ],
            'areaServed' => ['@type' => 'Country', 'name' => 'ایران'],
            'parentOrganization' => ['@id' => site_base_url() . '/#organization'],
        ],
        [
            '@type' => 'FAQPage',
            '@id' => site_base_url() . '/#faq',
            'mainEntity' => [
                [
                    '@type' => 'Question',
                    'name' => 'ثبت سفارش به معنی پرداخت است؟',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'خیر. ابتدا درخواست ثبت می‌شود و پرداخت فقط پس از بررسی، ارسال پیشنهاد و تأیید قرارداد انجام می‌گیرد.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'زمان اجرای پروژه چقدر است؟',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'پروژه‌های متمرکز از دو هفته و پروژه‌های کامل معمولاً بین چهار تا هشت هفته زمان می‌برند.',
                    ],
                ],
                [
                    '@type' => 'Question',
                    'name' => 'اگر هنوز دقیق ندانم چه می‌خواهم چه می‌شود؟',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => 'کافی است مسئله کسب‌وکار را توضیح دهید؛ انتخاب مسیر مناسب بخشی از جلسه رایگان اولیه است.',
                    ],
                ],
            ],
        ],
    ],
];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/conversion.php';
require __DIR__ . '/includes/enhancements.php';
require __DIR__ . '/includes/voice.php';
require __DIR__ . '/includes/footer.php';
