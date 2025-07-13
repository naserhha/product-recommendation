jQuery(document).ready(function($) {
    // اضافه کردن کلاس‌های ووکامرس به متاباکس
    $('#perfume_recommendation_meta_box').addClass('woocommerce_options_panel');

    // اضافه کردن استایل‌های ووکامرس به فرم‌ها
    $('.perfume-recommendation-meta-box select').addClass('wc-enhanced-select');

    // فعال‌سازی select2 برای فیلدهای انتخاب
    if ($.fn.select2) {
        $('.wc-enhanced-select').select2();
    }

    // اضافه کردن اعتبارسنجی به فرم
    $('.perfume-recommendation-meta-box select').on('change', function() {
        if ($(this).val() === '') {
            $(this).addClass('error');
        } else {
            $(this).removeClass('error');
        }
    });

    // اضافه کردن پیام خطا
    $('.perfume-recommendation-meta-box select').after('<span class="error-message" style="display: none; color: #dc3232; font-size: 12px; margin-top: 5px;">این فیلد الزامی است</span>');

    // نمایش پیام خطا
    $('.perfume-recommendation-meta-box select').on('invalid', function() {
        $(this).siblings('.error-message').show();
    });

    // مخفی کردن پیام خطا
    $('.perfume-recommendation-meta-box select').on('input', function() {
        $(this).siblings('.error-message').hide();
    });
}); 