# راهنمای انتشار سئو REDT

## تنظیم محیط اصلی

این متغیرها را در تنظیمات Apache/PHP یا کنترل‌پنل هاست تعریف کنید:

```env
APP_ENV=production
APP_BASE_URL=https://example.com/ArmanV2
APP_PATH=/ArmanV2
```

اگر سایت روی ریشه دامنه نصب است، `APP_BASE_URL` را بدون مسیر و `APP_PATH=/` قرار دهید.

## انتشار صفحه‌های داخلی

صفحه‌های موقت عمداً `noindex,follow` هستند و وارد sitemap نمی‌شوند. پس از انتشار محتوای کامل هر صفحه:

1. مشخصات همان route را در `data/routes.php` کامل کنید.
2. مقدار `indexable` را `true` کنید.
3. در `page.php` مقدار robots آن صفحه را به `index,follow` تغییر دهید.
4. اسکیما فقط برای اطلاعاتی اضافه شود که واقعاً روی صفحه دیده می‌شوند.

## کنترل نهایی

- آدرس `/robots.txt` و `/sitemap.php` باید با دامنه اصلی باز شوند.
- sitemap را در Google Search Console ثبت کنید.
- صفحه اصلی و صفحه‌های منتشرشده را با Rich Results Test و URL Inspection بررسی کنید.
- تلفن، ایمیل، نام و نشانی در فوتر و Organization schema باید یکسان بمانند.
- هر صفحه فقط یک H1، title و description اختصاصی داشته باشد.
