# REDT — گروه قرمز

وب‌سایت RTL آژانس REDT با PHP 8.2، HTML معنایی، CSS و JavaScript خالص. پروژه برای اجرا به npm یا Composer وابسته نیست.

## اجرا در Laragon یا XAMPP

پوشه را در `www/ArmanV2` یا `htdocs/ArmanV2` قرار دهید، Apache را اجرا کنید و آدرس زیر را باز کنید:

```text
http://localhost/ArmanV2/
```

مقادیر پیش‌فرض برای همین مسیر تنظیم شده‌اند. برای مسیر متفاوت، `APP_PATH` را در محیط وب‌سرور تعریف کنید.

## تنظیم production

```env
APP_ENV=production
APP_BASE_URL=https://example.com/ArmanV2
APP_PATH=/ArmanV2
```

در نصب روی ریشه دامنه، `APP_PATH=/` باشد. جزئیات انتشار و ایندکس‌کردن صفحه‌های داخلی در `SEO-DEPLOYMENT.md` آمده است.

## ساختار پروژه

- `includes/`: اجزای مشترک، SEO و رابط‌های صفحه
- `data/`: اطلاعات ناوبری و routeهای قابل ایندکس
- `assets/`: CSS، JavaScript، تصاویر، فونت و آیکون‌ها
- `api/`: endpointهای امن فرم سفارش و مشاوره
- `config/`: تنظیمات محیط و برنامه
- `storage/`: داده‌های runtime؛ دسترسی مستقیم وب به آن مسدود است

## قابلیت‌های اصلی

- صفحه اصلی رسمی، RTL، responsive و conversion-focused
- مسیر ثبت سفارش سه‌مرحله‌ای با CSRF، honeypot، validation و rate limit
- حالت روشن/تیره، انیمیشن‌های سازگار با reduced-motion و دسترسی کیبورد
- دستیار صوتی، نمونه‌کار، دوره، رضایت مشتری، FAQ و بنرهای کمپین
- title و description اختصاصی، canonical، Open Graph، Twitter Cards و cache busting
- JSON-LD یکپارچه برای Organization، WebSite، WebPage، ProfessionalService، FAQ و Breadcrumb
- sitemap و robots داینامیک، noindex برای staging و صفحه‌های ناقص
- فشرده‌سازی، cache، هدرهای امنیتی و جلوگیری از دسترسی مستقیم به فایل‌های داخلی

## کنترل سریع

```powershell
Get-ChildItem -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```

سپس `/robots.txt`، `/sitemap.php`، صفحه اصلی و صفحه 404 را از طریق Apache بررسی کنید.
