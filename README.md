# REDT — گروه قرمز

وب‌سایت RTL آژانس REDT با PHP 8.2، HTML معنایی، CSS و JavaScript خالص. پروژه برای اجرا به npm یا Composer وابسته نیست.

## معماری سه‌سایته

- `/` لندینگ اصلی و کوتاه برای دریافت لید و هدایت کاربر
- `/studio/` خدمات طراحی سایت، تبلیغات و نمونه‌کارها
- `/digital/` سفارش اکانت، اشتراک، ابزار و خدمات دیجیتال

هیچ مبلغی در رابط عمومی نمایش داده نمی‌شود؛ موجودی و شرایط هر درخواست ابتدا بررسی و سپس با کاربر هماهنگ می‌شود. در موبایل، ناوبری پایین صفحه و پنل‌های کشویی حس یک اپلیکیشن سبک را ایجاد می‌کنند.

## اجرا در Laragon یا XAMPP

پوشه را در `www/ArmanV2` یا `htdocs/ArmanV2` قرار دهید، Apache را اجرا کنید و آدرس زیر را باز کنید:

```text
http://localhost/ArmanV2/
```

مقادیر پیش‌فرض برای همین مسیر تنظیم شده‌اند. برای مسیر متفاوت، `APP_PATH` را در محیط وب‌سرور تعریف کنید.

## تنظیم production

```env
APP_ENV=production
APP_BASE_URL=https://example.com
DIGITAL_BASE_URL=https://digital.example.com
STUDIO_BASE_URL=https://studio.example.com
APP_PATH=/
```

هر سه دامنه باید در DNS و وب‌سرور به همین document root اشاره کنند. قوانین `.htaccess` درخواست ریشه‌ی `digital.*` و `studio.*` را به سطح مناسب هدایت می‌کنند. جزئیات انتشار و ایندکس‌کردن صفحه‌های داخلی در `SEO-DEPLOYMENT.md` آمده است.

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
- فروشگاه یکپارچه برای محصولات دیجیتال، خرید واسطه‌ای اکانت و سفارش تبلیغات
- فرم خرید واقعی با کد پیگیری، اعتبارسنجی سمت سرور و ثبت امن درخواست بدون دریافت رمز حساب
- پنل مدیریت محافظت‌شده با نمایش سفارش‌های واقعی، تغییر وضعیت، کنترل انتشار کاتالوگ و آمار بازدید

## ورود به پنل مدیریت

در محیط توسعه به مسیر `/admin/` بروید. اطلاعات پیش‌فرض فقط برای توسعه:

```text
username: admin
password: redt-admin
```

پیش از انتشار، `APP_ENV=production` و متغیرهای `ADMIN_USERNAME` و `ADMIN_PASSWORD` را در محیط وب‌سرور تنظیم کنید. برای امنیت بیشتر می‌توانید مقدار `ADMIN_PASSWORD` را به خروجی `password_hash` تغییر دهید؛ پنل هش‌های bcrypt و Argon2 را تشخیص می‌دهد.

## کنترل سریع

```powershell
Get-ChildItem -Recurse -Filter *.php | ForEach-Object { php -l $_.FullName }
```

سپس `/robots.txt`، `/sitemap.php`، صفحه اصلی و صفحه 404 را از طریق Apache بررسی کنید.
