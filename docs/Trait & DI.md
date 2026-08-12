۱. Trait چیست؟ (راه‌حل اشتراک‌گذاری کد بدون وراثت)
مشکل کجاست؟
در زبان PHP (و اکثر زبان‌های شیءگرا مثل C# یا Java)، یک کلاس تنها می‌تواند از یک کلاس دیگر وراثت (Inheritance) داشته باشد (Single Inheritance).

فرض کنید یک کلاس PostController و یک کلاس UserController دارید. هر دو نیاز دارند کارهایی مثل «آپلود فایل» یا «ارسال لوگ به سیستم» را انجام دهند. اگر بخواهید از وراثت استفاده کنید، مجبورید یک کلاس پایه مثل BaseController بسازید و همه متدها را آنجا بریزید؛ این کار باعث فربه شدن (Bloated) و شلوغی بی‌دلیل کلاس‌های پایه می‌شود.

تفاوت Trait با وراثت (Inheritance)
Trait مثل یک مجموعه ابزار (Toolbox) است. به شما اجازه می‌دهد متدها را درون آن تعریف کنید و سپس متدها را مستقیماً درون هر کلاسی که دوست دارید «کپی-پیست» حرفه‌ای بکنید!
مثال یک controller که میاد تعداد کارکتر رو چک می‌کنه کلی جاها میشه ازش استفاده کرد


DI: Dependency Injection
۲. Dependency Injection (DI) چیست؟ (تزریق وابستگی)
مشکل کجاست؟
وقتی کلاس A برای انجام کارهایش به کلاس B نیاز دارد، می‌گوییم کلاس A به کلاس B وابستگی (Dependency) دارد.

روش اشتباه و سنتی (Hardcoded Dependency):
کلاس خودش وابستگی‌اش را می‌سازد (با کلیدواژه new):
```php
class OrderController 
{
    public function processOrder() 
    {
        // ❌ وابستگی سخت و مستقیم به سیستم ایمیل‌دهی
        $mailer = new EmailSmsService(); 
        $mailer->sendReceipt();
    }
}
```

راه حل: الگوی Dependency Injection
در الگوی DI، کلاس خودش وابستگیهایش را نمیسازد، بلکه آنها را از بیرون دریافت میکند (به آن «تزریق» میشود).

تشبیه عالی: بهجای اینکه یک راننده تاکسی (کلاس)، خودش درون ماشین کارخانه خودروسازی بسازد (new Car())، ماشین از بیرون به او تحویل داده میشود! حالا راننده میتواند سوار پراید، بنز یا تسلا شود بدون اینکه خودش را تغییر دهد.
```php
// تعریف یک اینترفیس برای اتصال وابستگی‌ها
interface MailerInterface {
    public function send(string $message);
}

class OrderController 
{
    private MailerInterface $mailer;

    // ✅ تزریق وابستگی از طریق Constructor
    public function __construct(MailerInterface $mailer) 
    {
        $this->mailer = $mailer;
    }

    public function processOrder() 
    {
        // استفاده از وابستگی بدون اینکه بداند از چه کلاسی ساخته شده!
        $this->mailer->send("سفارش ثبت شد.");
    }
}
```