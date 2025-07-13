# Installation Guide

This guide provides detailed instructions for installing the Product Recommendation System plugin on your WordPress site.

## 📋 Prerequisites

Before installing the plugin, ensure your system meets these requirements:

### System Requirements

- **WordPress**: 5.0 or higher
- **WooCommerce**: 5.0 or higher
- **PHP**: 7.4 or higher
- **MySQL**: 5.6 or higher
- **Memory**: 64MB PHP memory limit (128MB recommended)

### Server Requirements

- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP Extensions**: 
  - `curl`
  - `json`
  - `mbstring`
  - `xml`
  - `zip`
- **Database**: MySQL 5.6+ or MariaDB 10.1+

## 🚀 Installation Methods

### Method 1: WordPress Admin (Recommended)

This is the easiest method for most users.

#### Step 1: Download the Plugin

1. Go to the [releases page](https://github.com/nasserhaji/product-recommendation/releases)
2. Download the latest version ZIP file
3. Save it to your computer

#### Step 2: Upload to WordPress

1. Log in to your WordPress admin panel
2. Navigate to **Plugins** → **Add New**
3. Click **Upload Plugin** at the top of the page
4. Click **Choose File** and select the downloaded ZIP file
5. Click **Install Now**
6. Wait for the installation to complete
7. Click **Activate Plugin**

#### Step 3: Verify Installation

1. Go to **Plugins** → **Installed Plugins**
2. Look for "Product Recommendation System"
3. Ensure it shows as "Active"

### Method 2: Manual Installation

Use this method if you prefer to upload files directly or if the admin upload doesn't work.

#### Step 1: Download and Extract

1. Download the plugin ZIP file from GitHub
2. Extract the ZIP file on your computer
3. You should have a folder named `product-recommendation`

#### Step 2: Upload Files

1. Connect to your server via FTP/SFTP
2. Navigate to `/wp-content/plugins/`
3. Upload the `product-recommendation` folder
4. Ensure the folder structure is: `/wp-content/plugins/product-recommendation/`

#### Step 3: Activate Plugin

1. Log in to your WordPress admin panel
2. Go to **Plugins** → **Installed Plugins**
3. Find "Product Recommendation System"
4. Click **Activate**

### Method 3: Git Clone (Developers)

This method is for developers who want to contribute to the project.

```bash
# Navigate to your WordPress plugins directory
cd wp-content/plugins/

# Clone the repository
git clone https://github.com/nasserhaji/product-recommendation.git

# Activate the plugin via WordPress admin or WP-CLI
wp plugin activate product-recommendation
```

## ⚙️ Post-Installation Setup

### Step 1: Verify WooCommerce

1. Ensure WooCommerce is installed and activated
2. Go to **WooCommerce** → **Settings** to verify it's working
3. If WooCommerce is not installed, install it first

### Step 2: Access Plugin Settings

1. In your WordPress admin, look for **Product Recommendation System** in the menu
2. Click on it to access the settings page
3. You should see the main configuration panel

### Step 3: Basic Configuration

1. **Set Default Language**: Choose your preferred language
2. **Configure Questions**: Set up your recommendation questions
3. **Customize Styling**: Adjust colors and appearance
4. **Test the Form**: Use the shortcode `[product_recommendation_form]`

## 🔧 Configuration

### Language Settings

1. Go to **Product Recommendation System** → **Settings**
2. Select your preferred language from the dropdown
3. Save changes

### Question Configuration

1. Navigate to **Settings** → **Base Questions**
2. Add, edit, or delete questions as needed
3. Set question types (text, select, radio, etc.)
4. Configure answer options for select/radio questions

### Styling Options

1. Go to **Settings** → **Appearance**
2. Customize:
   - Primary color
   - Font color
   - Button color
   - Button text color
3. Save your changes

### Product Characteristics

1. Go to any WooCommerce product
2. Scroll down to find the **Product Characteristics** section
3. Add characteristics that match your questions
4. Save the product

## 🧪 Testing the Installation

### Test 1: Plugin Activation

```php
// Check if plugin is active
if (is_plugin_active('product-recommendation/product-recommendation.php')) {
    echo 'Plugin is active';
} else {
    echo 'Plugin is not active';
}
```

### Test 2: Shortcode Display

1. Create a new page or post
2. Add the shortcode: `[product_recommendation_form]`
3. Publish and view the page
4. Verify the form displays correctly

### Test 3: Form Functionality

1. Fill out the recommendation form
2. Submit the form
3. Verify that recommendations appear
4. Check that products link correctly

### Test 4: Multi-language Support

1. Change your WordPress language setting
2. Refresh the form page
3. Verify text appears in the correct language

## 🚨 Troubleshooting

### Common Installation Issues

#### Issue: Plugin Won't Activate

**Symptoms**: Error message when trying to activate

**Solutions**:
```bash
# Check WordPress version
echo get_bloginfo('version');

# Check PHP version
echo phpversion();

# Check WooCommerce status
echo is_plugin_active('woocommerce/woocommerce.php');

# Fix file permissions
chmod 755 wp-content/plugins/product-recommendation/
chmod 644 wp-content/plugins/product-recommendation/*.php
```

#### Issue: Form Not Displaying

**Symptoms**: Shortcode shows nothing

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

#### Issue: Database Errors

**Symptoms**: Database-related error messages

**Solutions**:
```sql
-- Check database connection
SHOW TABLES LIKE 'wp_options';

-- Check plugin options
SELECT * FROM wp_options WHERE option_name LIKE 'product_recommendation%';
```

### Debug Mode

Enable WordPress debug mode for more information:

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

## 📊 System Check

Run this code to check your system compatibility:

```php
function check_system_compatibility() {
    $issues = array();
    
    // Check WordPress version
    if (version_compare(get_bloginfo('version'), '5.0', '<')) {
        $issues[] = 'WordPress version too old (requires 5.0+)';
    }
    
    // Check PHP version
    if (version_compare(PHP_VERSION, '7.4', '<')) {
        $issues[] = 'PHP version too old (requires 7.4+)';
    }
    
    // Check WooCommerce
    if (!class_exists('WooCommerce')) {
        $issues[] = 'WooCommerce not installed/activated';
    }
    
    // Check memory limit
    $memory_limit = ini_get('memory_limit');
    $memory_limit_bytes = wp_convert_hr_to_bytes($memory_limit);
    if ($memory_limit_bytes < 67108864) { // 64MB
        $issues[] = 'Memory limit too low (recommend 128MB+)';
    }
    
    // Check required PHP extensions
    $required_extensions = array('curl', 'json', 'mbstring', 'xml');
    foreach ($required_extensions as $ext) {
        if (!extension_loaded($ext)) {
            $issues[] = "PHP extension missing: $ext";
        }
    }
    
    return $issues;
}

$issues = check_system_compatibility();
if (empty($issues)) {
    echo 'System is compatible!';
} else {
    echo 'Issues found:';
    foreach ($issues as $issue) {
        echo "- $issue\n";
    }
}
```

## 🔄 Updating the Plugin

### Automatic Updates

1. Go to **Plugins** → **Installed Plugins**
2. Look for update notifications
3. Click **Update Now** when available

### Manual Updates

1. Download the latest version
2. Deactivate the current plugin
3. Delete the old plugin files
4. Upload the new version
5. Activate the plugin

### Backup Before Update

```bash
# Backup plugin files
cp -r wp-content/plugins/product-recommendation wp-content/plugins/product-recommendation-backup

# Backup database (optional)
wp db export backup.sql
```

## 📞 Getting Help

If you encounter issues during installation:

1. **Check the [Troubleshooting Guide](troubleshooting.md)**
2. **Search [GitHub Issues](https://github.com/nasserhaji/product-recommendation/issues)**
3. **Ask in [GitHub Discussions](https://github.com/nasserhaji/product-recommendation/discussions)**
4. **Contact Support**: support@mohammadnasser.com

## ✅ Installation Checklist

- [ ] WordPress 5.0+ installed
- [ ] WooCommerce 5.0+ installed and activated
- [ ] PHP 7.4+ installed
- [ ] Plugin files uploaded correctly
- [ ] Plugin activated successfully
- [ ] Settings page accessible
- [ ] Form displays correctly
- [ ] Recommendations work
- [ ] Multi-language support working
- [ ] Styling customization working

---

**Next Steps**: After installation, see the [Configuration Guide](configuration.md) for detailed setup instructions. 