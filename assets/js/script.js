jQuery(document).ready(function($) {
    const form = $('.product-recommendation-form');
    const resultsContainer = $('.product-recommendation-results');
    const loadingSpinner = $('.product-recommendation-loading');
    const progressBar = $('.product-recommendation-progress-bar');
    const stepLabels = $('.product-recommendation-step-label');
    const options = $('.product-recommendation-option');
    const nextBtn = $('.product-recommendation-next');
    const prevBtn = $('.product-recommendation-prev');
    const submitBtn = $('.product-recommendation-submit');
    const steps = $('.product-recommendation-step');
    let currentStep = 0;
    const totalSteps = steps.length;

    // تست مستقیم سیستم
            // Product Recommendation System Initialized
        // AJAX URL loaded
            // Nonce loaded successfully
    
    // تست اولیه AJAX - ساده‌تر
    $.post(productAjax.ajaxurl, {
        action: 'test_product_system',
        nonce: productAjax.nonce
    }, function(response) {
                        // Initial Test Response received
                if (response.success) {
                    // System is working!
                } else {
            console.error('System test failed:', response);
        }
    }).fail(function(xhr, status, error) {
        console.error('Initial Test Failed:', status, error);
        console.error('Response Text:', xhr.responseText);
    });

    // مدیریت انتخاب گزینه‌ها در فرم stepper
    options.on('click', function() {
        const option = $(this);
        const step = option.closest('.product-recommendation-step');
        const stepIndex = step.data('step');
        const value = option.data('value');
        
        // حذف انتخاب قبلی در این step
        step.find('.product-recommendation-option').removeClass('selected');
        
        // انتخاب گزینه جدید
        option.addClass('selected');
        
        // ذخیره مقدار در input hidden
        step.find('input[type="hidden"]').val(value);
        
        // فعال کردن دکمه بعدی
        updateNavigationButtons();
        
        // به‌روزرسانی progress bar
        updateProgressBar();
    });

    // مدیریت دکمه بعدی
    nextBtn.on('click', function() {
        if (currentStep < totalSteps - 1) {
            currentStep++;
            showStep(currentStep);
            updateNavigationButtons();
            updateProgressBar();
        }
    });

    // مدیریت دکمه قبلی
    prevBtn.on('click', function() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
            updateNavigationButtons();
            updateProgressBar();
        }
    });

    // نمایش step مشخص
    function showStep(stepIndex) {
        steps.removeClass('active');
        steps.eq(stepIndex).addClass('active');
        
        stepLabels.removeClass('active completed');
        stepLabels.each(function(index) {
            if (index < stepIndex) {
                $(this).addClass('completed');
            } else if (index === stepIndex) {
                $(this).addClass('active');
            }
        });
    }

    // به‌روزرسانی دکمه‌های ناوبری
    function updateNavigationButtons() {
        const currentStepElement = steps.eq(currentStep);
        const hasSelection = currentStepElement.find('input[type="hidden"]').val() !== '';
        
        // نمایش/مخفی کردن دکمه قبلی
        if (currentStep === 0) {
            prevBtn.hide();
        } else {
            prevBtn.show();
        }
        
        // نمایش/مخفی کردن دکمه بعدی و ارسال
        if (currentStep === totalSteps - 1) {
            nextBtn.hide();
            if (hasSelection) {
                submitBtn.show();
            } else {
                submitBtn.hide();
            }
        } else {
            nextBtn.show();
            submitBtn.hide();
            
            // فعال/غیرفعال کردن دکمه بعدی
            if (hasSelection) {
                nextBtn.prop('disabled', false);
            } else {
                nextBtn.prop('disabled', true);
            }
        }
    }

    // به‌روزرسانی progress bar
    function updateProgressBar() {
        const progress = ((currentStep + 1) / totalSteps) * 100;
        progressBar.css('width', progress + '%');
    }

    // ارسال فرم - ساده‌تر
    form.on('submit', function(e) {
        e.preventDefault();
        
                        // Form submitted
        
        // مستقیماً محصولات را دریافت کن
        getProducts();
        
        function getProducts() {
            let formData = {
                action: 'get_product_recommendations',
                nonce: productAjax.nonce
            };
            
            // داینامیک: همه input های hidden (سوالات) را اضافه کن
            form.find('input[type="hidden"]').each(function() {
                formData[$(this).attr('name')] = $(this).val();
            });

                            // Sending form data

            if (loadingSpinner.length) loadingSpinner.show();
            resultsContainer.hide();

            $.post(productAjax.ajaxurl, formData, function(response) {
                if (loadingSpinner.length) loadingSpinner.hide();
                // Products Response received
                
                if (response.success && response.data && response.data.length > 0) {
                    displayProducts(response.data);
                } else {
                    console.error('No products found:', response);
                    displayRandomProducts();
                }
            }).fail(function(xhr, status, error) {
                if (loadingSpinner.length) loadingSpinner.hide();
                console.error('Products AJAX Error:', status, error);
                console.error('Response:', xhr.responseText);
                displayRandomProducts();
            });
        }
        
        function displayProducts(products) {
            let html = '';
            
            // بررسی اینکه آیا محصولات تصادفی هستند
            const isRandom = products[0].is_random;
            if (isRandom) {
                html += '<div class="product-recommendation-random-notice">';
                html += '<p>در ادامه چند محصول محبوب از فروشگاه ما را مشاهده می‌کنید:</p>';
                html += '</div>';
            }
            
            html += '<div class="product-recommendation-grid">';
            products.forEach(function(product, index) {
                html += `
                    <div class="product-recommendation-item" style="animation-delay: ${index * 0.1}s">
                        <div class="product-recommendation-image">
                            <img src="${product.image || ''}" alt="${product.title}" onerror="this.src='${productAjax.placeholder_image || ''}'">
                        </div>
                        <div class="product-recommendation-content">
                            <h3>${product.title}</h3>
                            <div class="product-recommendation-price">${product.price}</div>
                            <div class="product-recommendation-description">${product.description}</div>
                            <div class="product-recommendation-actions">
                                <a href="${product.link}" class="button">مشاهده جزئیات</a>
                                <a href="${product.add_to_cart_url}" class="button">افزودن به سبد خرید</a>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            resultsContainer.html(html).show();
        }
        
        function displayRandomProducts() {
                            // Displaying random products
            
            // درخواست محصولات تصادفی
            $.post(productAjax.ajaxurl, {
                action: 'get_random_product_products',
                nonce: productAjax.nonce
            }, function(response) {
                // Random Products Response received
                if (response.success && response.data && response.data.length > 0) {
                    displayProducts(response.data);
                } else {
                    console.error('No random products found:', response);
                    displayError('هیچ محصولی در فروشگاه یافت نشد. لطفاً ابتدا محصولاتی را به فروشگاه اضافه کنید.');
                }
            }).fail(function(xhr, status, error) {
                console.error('Random Products AJAX Error:', status, error);
                console.error('Random Products Response:', xhr.responseText);
                displayError('خطا در دریافت نتایج. لطفا دوباره تلاش کنید.');
            });
        }
        
        function displayError(message) {
            resultsContainer.html('<p class="product-recommendation-error">' + message + '</p>').show();
        }
    });

    // مقداردهی اولیه
    updateNavigationButtons();
    updateProgressBar();
}); 