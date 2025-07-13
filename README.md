# Product Recommendation System for WooCommerce

[![WordPress](https://img.shields.io/badge/WordPress-5.0+-blue.svg)](https://wordpress.org/)
[![WooCommerce](https://img.shields.io/badge/WooCommerce-5.0+-green.svg)](https://woocommerce.com/)
[![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/License-GPL%20v2%20or%20later-red.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/Version-1.0.0-orange.svg)](https://github.com/nasserhaji/product-recommendation/releases)

A comprehensive and intelligent product recommendation system for WooCommerce that helps customers find the perfect products based on their preferences and characteristics.

---

## 🌍 Multilingual Descriptions

### 🇮🇷 Persian / فارسی
سیستم جامع و هوشمند توصیه محصول برای ووکامرس که به مشتریان کمک می‌کند تا محصولات مناسب را بر اساس ترجیحات و ویژگی‌های خود پیدا کنند.

### 🇸🇦 Arabic / العربية
نظام توصية المنتجات الشامل والذكي لـ WooCommerce الذي يساعد العملاء في العثور على المنتجات المثالية بناءً على تفضيلاتهم وخصائصهم.

### 🇨🇳 Chinese / 中文
一个全面的智能产品推荐系统，适用于WooCommerce，帮助客户根据其偏好和特征找到完美的产品。

### 🇷🇺 Russian / Русский
Комплексная и интеллектуальная система рекомендаций продуктов для WooCommerce, которая помогает клиентам найти идеальные продукты на основе их предпочтений и характеристик.

---

## 🌟 Features

### 🎯 Smart Recommendation Engine
- **Interactive Questionnaire**: Dynamic form that guides customers to their ideal products
- **Intelligent Matching**: Advanced algorithm that matches customer preferences with product characteristics
- **Customizable Questions**: Add, edit, delete, and reorder questions from the admin panel
- **Random Fallback**: Shows random products when no exact matches are found

### 🌍 Multi-Language Support
- **Persian/Farsi**: Full RTL support with native Persian interface
- **English**: Complete English localization
- **Russian**: Full Russian language support
- **Chinese**: Traditional and Simplified Chinese support
- **Arabic**: Complete Arabic RTL support
- **Extensible**: Easy to add new languages using POT files

### 🎨 Modern Design
- **Responsive Design**: Works perfectly on all devices (desktop, tablet, mobile)
- **Customizable Styling**: Adjust colors, fonts, and appearance to match your brand
- **RTL Support**: Full right-to-left language support
- **Modern UI**: Clean, professional interface with smooth animations

### 🔧 Easy Integration
- **Simple Shortcode**: Use `[product_recommendation_form]` anywhere on your site
- **WooCommerce Integration**: Seamless integration with existing WooCommerce products
- **Admin Management**: Complete control over questions, products, and settings
- **AJAX Powered**: Fast, dynamic responses without page reloads

## 📋 Requirements

- **WordPress**: 5.0 or higher
- **WooCommerce**: 5.0 or higher
- **PHP**: 7.4 or higher
- **MySQL**: 5.6 or higher

## 🚀 Installation

### Method 1: WordPress Admin (Recommended)

1. Download the plugin ZIP file from the [releases page](https://github.com/nasserhaji/product-recommendation/releases)
2. Go to your WordPress admin panel → Plugins → Add New
3. Click "Upload Plugin" and select the downloaded ZIP file
4. Click "Install Now" and then "Activate Plugin"

### Method 2: Manual Installation

1. Download the plugin files
2. Upload the `product-recommendation` folder to `/wp-content/plugins/`
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to 'Product Recommendation System' in the admin menu
5. Configure your questions and settings

### Method 3: Git Clone

```bash
cd wp-content/plugins/
git clone https://github.com/nasserhaji/product-recommendation.git
```

## ⚙️ Configuration

### 1. Basic Setup

1. **Activate the Plugin**: Go to Plugins → Product Recommendation System → Activate
2. **Access Settings**: Navigate to Product Recommendation System in the admin menu
3. **Configure Questions**: Set up your recommendation questions in the settings panel
4. **Add Product Characteristics**: Assign characteristics to your WooCommerce products

### 2. Customizing Questions

```php
// Example of adding custom questions programmatically
add_filter('product_recommendation_base_questions', function($questions) {
    $questions[] = [
        'id' => 'custom_question',
        'question' => 'What is your preferred budget range?',
        'type' => 'select',
        'options' => [
            'low' => 'Under $50',
            'medium' => '$50 - $200',
            'high' => 'Over $200'
        ]
    ];
    return $questions;
});
```

### 3. Styling Customization

The plugin supports custom CSS variables for easy styling:

```css
:root {
    --pr-primary-color: #007cba;
    --pr-font-color: #333333;
    --pr-button-color: #007cba;
    --pr-button-text-color: #ffffff;
}
```

## 📖 Usage

### Adding the Recommendation Form

Use the shortcode anywhere on your site:

```php
// In a page or post
[product_recommendation_form]

// In a PHP template
echo do_shortcode('[product_recommendation_form]');

// With custom attributes
[product_recommendation_form title="Find Your Perfect Product"]
```

### Programmatic Usage

```php
// Get product recommendations programmatically
$answers = [
    'budget' => 'medium',
    'style' => 'modern',
    'size' => 'large'
];

$recommendations = apply_filters('product_recommendation_get_products', [], $answers);
```

### Hooks and Filters

```php
// Customize the recommendation algorithm
add_filter('product_recommendation_matching_algorithm', function($algorithm, $answers) {
    // Your custom logic here
    return $algorithm;
}, 10, 2);

// Add custom product characteristics
add_filter('product_recommendation_product_characteristics', function($characteristics, $product_id) {
    $characteristics['custom_field'] = get_post_meta($product_id, 'custom_field', true);
    return $characteristics;
}, 10, 2);
```

## 🌐 Localization

### Supported Languages

- **Persian/Farsi**: `fa_IR`, `fa_AF`, `fa_TJ`
- **English**: `en_US`, `en_GB`, `en_CA`, `en_AU`, and more
- **Russian**: `ru_RU`, `ru_UA`, `ru_BY`, `ru_KZ`, and more
- **Chinese**: `zh_CN`, `zh_TW`, `zh_HK`, `zh_SG`, and more
- **Arabic**: `ar_SA`, `ar_EG`, `ar_AE`, `ar_JO`, and more

### Adding New Languages

1. Copy the `languages/product-recommendation.pot` file
2. Translate the strings using a tool like Poedit
3. Save as `product-recommendation-{locale}.po`
4. Compile to `product-recommendation-{locale}.mo`
5. Place both files in the `languages` directory

---

## 🌍 Multilingual Detailed Descriptions

### 🇮🇷 Persian / فارسی

#### ویژگی‌های اصلی
- **موتور توصیه هوشمند**: الگوریتم پیشرفته برای تطبیق ترجیحات مشتریان با ویژگی‌های محصولات
- **فرم تعاملی**: پرسشنامه پویا که مشتریان را به محصولات ایده‌آل هدایت می‌کند
- **سوالات قابل تنظیم**: امکان اضافه کردن، ویرایش، حذف و مرتب‌سازی سوالات از پنل مدیریت
- **پشتیبانی چندزبانه**: پشتیبانی کامل از فارسی، انگلیسی، روسی، چینی و عربی
- **طراحی واکنش‌گرا**: سازگار با تمام دستگاه‌ها (دسکتاپ، تبلت، موبایل)
- **پشتیبانی RTL**: پشتیبانی کامل از زبان‌های راست به چپ
- **یکپارچه‌سازی آسان**: استفاده از شورت‌کد `[product_recommendation_form]` در هر جای سایت

#### نحوه استفاده
```php
// استفاده در صفحه یا پست
[product_recommendation_form]

// استفاده در قالب PHP
echo do_shortcode('[product_recommendation_form]');

// با ویژگی‌های سفارشی
[product_recommendation_form title="محصول مناسب خود را پیدا کنید"]
```

#### نصب و راه‌اندازی
1. فایل ZIP افزونه را از صفحه انتشارات دانلود کنید
2. به پنل مدیریت وردپرس بروید → افزونه‌ها → افزودن جدید
3. روی "بارگذاری افزونه" کلیک کنید و فایل ZIP را انتخاب کنید
4. روی "نصب اکنون" و سپس "فعال‌سازی افزونه" کلیک کنید

### 🇸🇦 Arabic / العربية

#### الميزات الرئيسية
- **محرك التوصية الذكي**: خوارزمية متقدمة لمطابقة تفضيلات العملاء مع خصائص المنتجات
- **النموذج التفاعلي**: استبيان ديناميكي يوجه العملاء إلى منتجاتهم المثالية
- **الأسئلة القابلة للتخصيص**: إمكانية إضافة وتحرير وحذف وإعادة ترتيب الأسئلة من لوحة الإدارة
- **الدعم متعدد اللغات**: دعم كامل للفارسية والإنجليزية والروسية والصينية والعربية
- **التصميم المتجاوب**: متوافق مع جميع الأجهزة (سطح المكتب والتابلت والهاتف المحمول)
- **دعم RTL**: دعم كامل للغات من اليمين إلى اليسار
- **التكامل السهل**: استخدام الشورت كود `[product_recommendation_form]` في أي مكان في الموقع

#### كيفية الاستخدام
```php
// الاستخدام في الصفحة أو المنشور
[product_recommendation_form]

// الاستخدام في قالب PHP
echo do_shortcode('[product_recommendation_form]');

// مع الخصائص المخصصة
[product_recommendation_form title="ابحث عن منتجك المثالي"]
```

#### التثبيت والإعداد
1. قم بتحميل ملف ZIP الإضافة من صفحة الإصدارات
2. اذهب إلى لوحة إدارة ووردبريس → الإضافات → إضافة جديدة
3. انقر على "رفع الإضافة" واختر ملف ZIP
4. انقر على "تثبيت الآن" ثم "تفعيل الإضافة"

### 🇨🇳 Chinese / 中文

#### 主要功能
- **智能推荐引擎**: 先进的算法，将客户偏好与产品特性匹配
- **交互式表单**: 动态问卷，引导客户找到理想产品
- **可自定义问题**: 从管理面板添加、编辑、删除和重新排序问题
- **多语言支持**: 完整支持波斯语、英语、俄语、中文和阿拉伯语
- **响应式设计**: 兼容所有设备（桌面、平板、手机）
- **RTL支持**: 完整支持从右到左的语言
- **易于集成**: 在网站任何地方使用短代码 `[product_recommendation_form]`

#### 使用方法
```php
// 在页面或文章中
[product_recommendation_form]

// 在PHP模板中
echo do_shortcode('[product_recommendation_form]');

// 使用自定义属性
[product_recommendation_form title="找到您的完美产品"]
```

#### 安装和设置
1. 从发布页面下载插件ZIP文件
2. 进入WordPress管理面板 → 插件 → 添加新插件
3. 点击"上传插件"并选择下载的ZIP文件
4. 点击"立即安装"然后"激活插件"

### 🇷🇺 Russian / Русский

#### Основные возможности
- **Умный движок рекомендаций**: Продвинутый алгоритм для сопоставления предпочтений клиентов с характеристиками продуктов
- **Интерактивная форма**: Динамическая анкета, которая направляет клиентов к их идеальным продуктам
- **Настраиваемые вопросы**: Возможность добавлять, редактировать, удалять и переупорядочивать вопросы из панели администратора
- **Многоязычная поддержка**: Полная поддержка персидского, английского, русского, китайского и арабского языков
- **Адаптивный дизайн**: Совместимость со всеми устройствами (десктоп, планшет, мобильный)
- **Поддержка RTL**: Полная поддержка языков справа налево
- **Простая интеграция**: Использование шорткода `[product_recommendation_form]` в любом месте сайта

#### Как использовать
```php
// В странице или записи
[product_recommendation_form]

// В PHP шаблоне
echo do_shortcode('[product_recommendation_form]');

// С пользовательскими атрибутами
[product_recommendation_form title="Найдите свой идеальный продукт"]
```

#### Установка и настройка
1. Скачайте ZIP файл плагина со страницы релизов
2. Перейдите в панель управления WordPress → Плагины → Добавить новый
3. Нажмите "Загрузить плагин" и выберите скачанный ZIP файл
4. Нажмите "Установить сейчас" и затем "Активировать плагин"

---

## 🛠️ Development

### Project Structure

```
product-recommendation/
├── assets/
│   ├── css/
│   │   ├── admin.css
│   │   └── style.css
│   └── js/
│       ├── admin.js
│       └── script.js
├── includes/
│   ├── class-product-database.php
│   ├── class-product-form.php
│   ├── class-product-recommendation.php
│   ├── class-product-recommendation-activator.php
│   ├── class-product-recommendation-admin.php
│   ├── class-product-recommendation-ajax.php
│   ├── class-product-recommendation-deactivator.php
│   ├── class-product-recommendation-engine.php
│   ├── class-product-recommendation-frontend.php
│   ├── class-product-recommendation-product.php
│   └── class-product-recommendation-settings.php
├── languages/
│   ├── product-recommendation.pot
│   └── [translation files]
├── product-recommendation.php
├── readme.txt
└── uninstall.php
```

### Building for Development

```bash
# Clone the repository
git clone https://github.com/nasserhaji/product-recommendation.git

# Install development dependencies (if any)
composer install

# Run tests (if available)
phpunit

# Build for production
# The plugin is ready to use as-is
```

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork the repository**
2. **Create a feature branch**: `git checkout -b feature/amazing-feature`
3. **Make your changes**: Follow WordPress coding standards
4. **Test thoroughly**: Ensure compatibility with WordPress and WooCommerce
5. **Commit your changes**: `git commit -m 'Add amazing feature'`
6. **Push to the branch**: `git push origin feature/amazing-feature`
7. **Open a Pull Request**: Provide detailed description of changes

### Coding Standards

- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- Use meaningful variable and function names
- Add proper documentation for all functions
- Include inline comments for complex logic
- Test on multiple WordPress and WooCommerce versions

## 📝 Changelog

### [1.0.0] - 2024-01-XX

#### Added
- Initial release of Product Recommendation System
- Smart product recommendation form with interactive questionnaire
- Multi-language support (Persian, English, Russian, Chinese, Arabic)
- Customizable questions and settings from admin panel
- WooCommerce integration with product characteristics
- Responsive design with RTL support
- Admin management panel with comprehensive settings
- AJAX-powered dynamic recommendations
- Random product fallback system
- Customizable styling options

#### Features
- Interactive recommendation form
- Multi-language localization
- Responsive design
- WooCommerce integration
- Admin management panel
- Customizable styling
- RTL language support

## 📄 License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **WordPress Community**: For the amazing platform
- **WooCommerce Team**: For the excellent e-commerce solution
- **Contributors**: Everyone who has contributed to this project
- **Translators**: Community members who have provided translations

## 📞 Support

- **Documentation**: [GitHub Wiki](https://github.com/nasserhaji/product-recommendation/wiki)
- **Issues**: [GitHub Issues](https://github.com/nasserhaji/product-recommendation/issues)
- **Discussions**: [GitHub Discussions](https://github.com/nasserhaji/product-recommendation/discussions)
- **Author**: [Mohammad Nasser Haji Hashemabad](https://mohammadnasser.com/)

## ⭐ Star History

[![Star History Chart](https://api.star-history.com/svg?repos=nasserhaji/product-recommendation&type=Date)](https://star-history.com/#nasserhaji/product-recommendation&Date)

---

**Made with ❤️ for the WordPress community** 