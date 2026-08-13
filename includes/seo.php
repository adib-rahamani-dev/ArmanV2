<?php
declare(strict_types=1);

function seo_meta(array $overrides = []): array
{
    $defaults = [
        'title' => 'آژانس برندینگ و طراحی سایت REDT | گروه قرمز',
        'description' => SITE_DESCRIPTION,
        'canonical' => current_canonical_url(),
        'robots' => APP_ENV === 'production'
            ? 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1'
            : 'noindex,nofollow',
        'type' => 'website',
        'image' => absolute_url('assets/images/seo/redt-social.jpg'),
        'image_alt' => 'نمایی از فرایند طراحی کمپین و هویت برند در REDT',
        'breadcrumbs' => [],
        'schema' => [],
    ];
    $meta = array_replace($defaults, array_filter($overrides, static fn ($value) => $value !== null));
    $meta['title'] = trim(strip_tags((string) $meta['title']));
    $meta['description'] = trim(preg_replace('/\s+/u', ' ', strip_tags((string) $meta['description'])) ?? '');
    $meta['canonical'] = (string) $meta['canonical'];
    $meta['image'] = absolute_url((string) $meta['image']);
    return $meta;
}

function seo_schema_graph(array $meta): array
{
    $base = site_base_url();
    $organizationId = $base . '/#organization';
    $websiteId = $base . '/#website';
    $webpageId = rtrim((string) $meta['canonical'], '/') . '/#webpage';
    $graph = [
        [
            '@type' => 'Organization', '@id' => $organizationId, 'name' => APP_NAME,
            'alternateName' => SITE_NAME_FA, 'url' => $base . '/',
            'logo' => ['@type' => 'ImageObject', 'url' => absolute_url('assets/icons/logo.svg'), 'width' => 512, 'height' => 512],
            'email' => 'hello@redt.ir', 'telephone' => '+98-21-8876-5432',
            'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'تهران', 'addressCountry' => 'IR'],
        ],
        [
            '@type' => 'WebSite', '@id' => $websiteId, 'url' => $base . '/', 'name' => APP_NAME,
            'alternateName' => SITE_NAME_FA, 'inLanguage' => 'fa-IR', 'publisher' => ['@id' => $organizationId],
        ],
        [
            '@type' => 'WebPage', '@id' => $webpageId, 'url' => $meta['canonical'], 'name' => $meta['title'],
            'description' => $meta['description'], 'isPartOf' => ['@id' => $websiteId], 'about' => ['@id' => $organizationId],
            'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => $meta['image']], 'inLanguage' => 'fa-IR',
        ],
    ];
    if ($meta['breadcrumbs'] !== []) {
        $items = [];
        foreach (array_values($meta['breadcrumbs']) as $index => $crumb) {
            $items[] = ['@type' => 'ListItem', 'position' => $index + 1, 'name' => (string) $crumb['name'], 'item' => absolute_url((string) $crumb['url'])];
        }
        $graph[] = ['@type' => 'BreadcrumbList', '@id' => rtrim((string) $meta['canonical'], '/') . '/#breadcrumb', 'itemListElement' => $items];
    }
    foreach ((array) $meta['schema'] as $schema) if (is_array($schema)) $graph[] = $schema;
    return ['@context' => 'https://schema.org', '@graph' => $graph];
}

function seo_json_ld(array $meta): string
{
    return (string) json_encode(seo_schema_graph($meta), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_THROW_ON_ERROR);
}
