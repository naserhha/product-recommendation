# Support Guide

Welcome to the Product Recommendation System support guide! This document provides comprehensive information about getting help, troubleshooting common issues, and finding resources.

## 📞 Getting Help

### 🚀 Quick Start

1. **Check the Documentation**: Start with the [README.md](README.md) for installation and basic usage
2. **Search Issues**: Check [GitHub Issues](https://github.com/nasserhaji/product-recommendation/issues) for similar problems
3. **Ask the Community**: Use [GitHub Discussions](https://github.com/nasserhaji/product-recommendation/discussions) for questions
4. **Report Bugs**: Create a new issue for bugs or feature requests

### 📧 Contact Information

| Type | Contact | Response Time |
|------|---------|---------------|
| **General Support** | support@mohammadnasser.com | 24-48 hours |
| **Bug Reports** | [GitHub Issues](https://github.com/nasserhaji/product-recommendation/issues) | 24-72 hours |
| **Feature Requests** | [GitHub Discussions](https://github.com/nasserhaji/product-recommendation/discussions) | 1-2 weeks |
| **Security Issues** | security@mohammadnasser.com | 24 hours |
| **Code of Conduct** | conduct@mohammadnasser.com | 24 hours |
| **Emergency** | emergency@mohammadnasser.com | 12 hours |

## 🔧 Troubleshooting Guide

### Common Issues

#### 1. Plugin Won't Activate

**Symptoms**: Plugin activation fails with error message

**Possible Causes**:
- WordPress version too old (requires 5.0+)
- WooCommerce not installed/activated
- PHP version too old (requires 7.4+)
- File permissions issues

**Solutions**:
```bash
# Check WordPress version
echo get_bloginfo('version');

# Check WooCommerce status
echo is_plugin_active('woocommerce/woocommerce.php');

# Check PHP version
echo phpversion();

# Fix file permissions
chmod 755 wp-content/plugins/product-recommendation/
chmod 644 wp-content/plugins/product-recommendation/*.php
```

#### 2. Form Not Displaying

**Symptoms**: Shortcode `[product_recommendation_form]` shows nothing

**Possible Causes**:
- WooCommerce not active
- JavaScript errors
- CSS conflicts
- Theme compatibility issues

**Solutions**:
```php
// Check if WooCommerce is active
if (class_exists('WooCommerce')) {
    echo 'WooCommerce is active';
} else {
    echo 'WooCommerce is not active';
}

// Check for JavaScript errors in browser console
// Check for CSS conflicts
```

#### 3. Recommendations Not Working

**Symptoms**: Form submits but no products are recommended

**Possible Causes**:
- No products with characteristics assigned
- Database issues
- AJAX errors
- PHP memory limits

**Solutions**:
```php
// Check if products have characteristics
$products = wc_get_products(array(
    'limit' => -1,
    'meta_query' => array(
        array(
            'key' => '_product_characteristics',
            'compare' => 'EXISTS'
        )
    )
));
echo 'Products with characteristics: ' . count($products);

// Check PHP memory limit
echo 'Memory limit: ' . ini_get('memory_limit');

// Check AJAX response in browser network tab
```

#### 4. Language Issues

**Symptoms**: Text not displaying in correct language

**Possible Causes**:
- Language files missing
- WordPress language setting incorrect
- Translation files not compiled

**Solutions**:
```php
// Check current locale
echo get_locale();

// Check if language file exists
$mofile = WP_PLUGIN_DIR . '/product-recommendation/languages/product-recommendation-' . get_locale() . '.mo';
echo file_exists($mofile) ? 'Language file exists' : 'Language file missing';

// Force reload text domain
load_plugin_textdomain('product-recommendation', false, dirname(plugin_basename(__FILE__)) . '/languages');
```

#### 5. Styling Issues

**Symptoms**: Form looks broken or doesn't match theme

**Possible Causes**:
- CSS conflicts with theme
- Custom CSS not loading
- RTL language issues

**Solutions**:
```css
/* Override theme styles */
.product-recommendation-form {
    /* Your custom styles */
    font-family: inherit !important;
    color: inherit !important;
}

/* Fix RTL issues */
.product-recommendation-form[dir="rtl"] {
    text-align: right;
}
```

### Debug Mode

Enable WordPress debug mode to get more information:

```php
// Add to wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

// Check debug log
tail -f wp-content/debug.log
```

### Performance Issues

#### Slow Loading

**Solutions**:
- Enable caching
- Optimize images
- Use CDN
- Check server resources

#### Memory Issues

**Solutions**:
```php
// Increase memory limit in wp-config.php
define('WP_MEMORY_LIMIT', '256M');

// Check memory usage
echo 'Memory usage: ' . memory_get_usage(true) / 1024 / 1024 . ' MB';
```

## 📚 Documentation

### User Documentation

- **[README.md](README.md)**: Complete user guide with installation and usage
- **[Installation Guide](docs/installation.md)**: Step-by-step installation instructions
- **[Configuration Guide](docs/configuration.md)**: Detailed configuration options
- **[FAQ](docs/faq.md)**: Frequently asked questions

### Developer Documentation

- **[API Reference](docs/api.md)**: Complete API documentation
- **[Hooks and Filters](docs/hooks.md)**: Available hooks and filters
- **[Customization Guide](docs/customization.md)**: How to customize the plugin
- **[Translation Guide](docs/translation.md)**: How to translate the plugin

### Video Tutorials

- [Installation Tutorial](https://youtube.com/watch?v=example1)
- [Configuration Tutorial](https://youtube.com/watch?v=example2)
- [Customization Tutorial](https://youtube.com/watch?v=example3)

## 🛠️ Development Support

### Setting Up Development Environment

```bash
# Clone the repository
git clone https://github.com/nasserhaji/product-recommendation.git

# Navigate to WordPress plugins directory
cd wp-content/plugins/

# Create symbolic link for development
ln -s /path/to/product-recommendation product-recommendation

# Activate the plugin
wp plugin activate product-recommendation
```

### Testing

```bash
# Run PHP tests (if available)
phpunit

# Check coding standards
phpcs --standard=WordPress includes/

# Check for security issues
phpcs --standard=Security includes/
```

### Debugging

```php
// Enable plugin debugging
define('PRODUCT_RECOMMENDATION_DEBUG', true);

// Log debug information
if (defined('PRODUCT_RECOMMENDATION_DEBUG') && PRODUCT_RECOMMENDATION_DEBUG) {
    error_log('Product Recommendation Debug: ' . $message);
}
```

## 🌐 Community Support

### GitHub Resources

- **[Issues](https://github.com/nasserhaji/product-recommendation/issues)**: Report bugs and request features
- **[Discussions](https://github.com/nasserhaji/product-recommendation/discussions)**: Ask questions and share ideas
- **[Wiki](https://github.com/nasserhaji/product-recommendation/wiki)**: Community-maintained documentation
- **[Releases](https://github.com/nasserhaji/product-recommendation/releases)**: Download latest versions

### Social Media

- **Twitter**: [@nasserhaji](https://twitter.com/nasserhaji)
- **LinkedIn**: [Mohammad Nasser](https://linkedin.com/in/mohammadnasser)
- **Website**: [mohammadnasser.com](https://mohammadnasser.com)

### Community Guidelines

- **Be respectful**: Treat everyone with respect
- **Be helpful**: Help others when you can
- **Be patient**: Remember that maintainers are volunteers
- **Follow the [Code of Conduct](CODE_OF_CONDUCT.md)**

## 📋 Issue Templates

### Bug Report Template

```markdown
## Bug Description
Clear description of the issue

## Steps to Reproduce
1. Go to '...'
2. Click on '...'
3. Scroll down to '...'
4. See error

## Expected Behavior
What should happen

## Actual Behavior
What actually happens

## Environment
- WordPress: X.X.X
- WooCommerce: X.X.X
- PHP: X.X.X
- Plugin Version: X.X.X
- Browser: X.X.X

## Additional Information
Any other relevant information
```

### Feature Request Template

```markdown
## Feature Description
Clear description of the feature

## Use Case
How would this feature be used?

## Benefits
What benefits would this feature provide?

## Implementation Ideas
Any ideas for how this could be implemented?

## Priority
High/Medium/Low
```

## 🔒 Security Support

### Reporting Security Issues

**IMPORTANT**: Do not create public issues for security vulnerabilities.

- **Email**: security@mohammadnasser.com
- **Subject**: `[SECURITY] Product Recommendation System - [Description]`
- **Response Time**: 24 hours

### Security Best Practices

1. **Keep Updated**: Always use the latest version
2. **Strong Passwords**: Use strong, unique passwords
3. **Regular Backups**: Maintain regular backups
4. **Security Plugins**: Consider using security plugins
5. **HTTPS**: Always use HTTPS in production

## 📊 System Requirements

### Minimum Requirements

- **WordPress**: 5.0 or higher
- **WooCommerce**: 5.0 or higher
- **PHP**: 7.4 or higher
- **MySQL**: 5.6 or higher
- **Memory**: 64MB PHP memory limit

### Recommended Requirements

- **WordPress**: 6.0 or higher
- **WooCommerce**: 6.0 or higher
- **PHP**: 8.0 or higher
- **MySQL**: 8.0 or higher
- **Memory**: 128MB PHP memory limit

### Browser Support

- **Chrome**: 90+
- **Firefox**: 88+
- **Safari**: 14+
- **Edge**: 90+
- **Mobile**: iOS Safari 14+, Chrome Mobile 90+

## 📈 Performance Tips

### Optimization

1. **Enable Caching**: Use WordPress caching plugins
2. **Optimize Images**: Compress and resize images
3. **Use CDN**: Serve static files from CDN
4. **Database Optimization**: Regular database cleanup
5. **Minimize Plugins**: Only use necessary plugins

### Monitoring

```php
// Monitor plugin performance
$start_time = microtime(true);
// Your code here
$end_time = microtime(true);
$execution_time = $end_time - $start_time;
error_log("Plugin execution time: $execution_time seconds");
```

## 🙏 Acknowledgments

We thank all contributors, users, and community members who help make the Product Recommendation System better through their feedback, bug reports, and contributions.

## 📄 License

This support guide is part of the Product Recommendation System project and is licensed under the same terms as the main project (GPL v2 or later).

---

**Last Updated**: January 2024  
**Next Review**: April 2024 