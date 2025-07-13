# Contributing to Product Recommendation System

Thank you for your interest in contributing to the Product Recommendation System! This document provides guidelines and information for contributors.

## 🤝 How to Contribute

We welcome contributions from the community! Here are the main ways you can contribute:

### 🐛 Reporting Bugs

1. **Check existing issues** - Search the [Issues](https://github.com/nasserhaji/product-recommendation/issues) to see if the bug has already been reported
2. **Create a new issue** - Use the bug report template and provide:
   - Clear description of the problem
   - Steps to reproduce
   - Expected vs actual behavior
   - Environment details (WordPress version, WooCommerce version, PHP version)
   - Screenshots if applicable

### 💡 Suggesting Features

1. **Check existing discussions** - Search the [Discussions](https://github.com/nasserhaji/product-recommendation/discussions) for similar ideas
2. **Create a feature request** - Use the feature request template and include:
   - Clear description of the feature
   - Use cases and benefits
   - Implementation suggestions (if any)
   - Priority level

### 🔧 Code Contributions

1. **Fork the repository**
2. **Create a feature branch**: `git checkout -b feature/amazing-feature`
3. **Make your changes** following the coding standards below
4. **Test thoroughly** on multiple WordPress and WooCommerce versions
5. **Commit your changes**: `git commit -m 'Add amazing feature'`
6. **Push to the branch**: `git push origin feature/amazing-feature`
7. **Open a Pull Request** with detailed description

## 📋 Development Setup

### Prerequisites

- WordPress 5.0+
- WooCommerce 5.0+
- PHP 7.4+
- Git
- Local development environment (XAMPP, MAMP, Local by Flywheel, etc.)

### Local Development

```bash
# Clone the repository
git clone https://github.com/nasserhaji/product-recommendation.git

# Navigate to your WordPress plugins directory
cd wp-content/plugins/

# Create a symbolic link (optional, for development)
ln -s /path/to/product-recommendation product-recommendation

# Or copy the files directly
cp -r /path/to/product-recommendation ./
```

### Testing Environment

1. **Set up a test WordPress site** with WooCommerce
2. **Activate the plugin** and configure basic settings
3. **Test all functionality** including:
   - Form rendering
   - Question management
   - Product recommendations
   - Multi-language support
   - Responsive design
   - AJAX functionality

## 📝 Coding Standards

### PHP Standards

We follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/):

```php
<?php
/**
 * Function description.
 *
 * @param string $param1 Description of parameter.
 * @param int    $param2 Description of parameter.
 * @return array Description of return value.
 */
function my_function( $param1, $param2 ) {
    // Code here
    return array();
}
```

### File Naming

- **Classes**: `class-product-recommendation-{name}.php`
- **Functions**: Use snake_case
- **Variables**: Use snake_case
- **Constants**: Use UPPER_SNAKE_CASE

### Code Organization

```php
<?php
/**
 * Plugin Name: Product Recommendation System
 * Description: A comprehensive product recommendation system for WooCommerce
 * Version: 1.0.0
 * Author: Mohammad Nasser Haji Hashemabad
 * License: GPL v2 or later
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Class definition
class Product_Recommendation_Example {
    
    /**
     * Constructor
     */
    public function __construct() {
        // Constructor code
    }
    
    /**
     * Initialize the class
     */
    public function init() {
        // Initialization code
    }
    
    /**
     * Example method
     *
     * @param string $param Parameter description.
     * @return bool
     */
    public function example_method( $param ) {
        // Method implementation
        return true;
    }
}
```

### Security Guidelines

1. **Always sanitize inputs**:
   ```php
   $user_input = sanitize_text_field( $_POST['user_input'] );
   ```

2. **Always escape outputs**:
   ```php
   echo esc_html( $output );
   echo esc_url( $url );
   echo wp_kses_post( $html_content );
   ```

3. **Use nonces for forms**:
   ```php
   wp_nonce_field( 'action_name', 'nonce_field' );
   ```

4. **Check user capabilities**:
   ```php
   if ( ! current_user_can( 'manage_options' ) ) {
       return;
   }
   ```

### JavaScript Standards

```javascript
/**
 * Function description
 *
 * @param {string} param1 - Parameter description
 * @param {number} param2 - Parameter description
 * @returns {boolean} Return description
 */
function myFunction( param1, param2 ) {
    // Code here
    return true;
}

// Use jQuery when available
jQuery( document ).ready( function( $ ) {
    // jQuery code here
});
```

### CSS Standards

```css
/* Component: Product Recommendation Form */
.product-recommendation-form {
    /* Styles */
}

.product-recommendation-form__question {
    /* Nested styles */
}

/* Responsive design */
@media ( max-width: 768px ) {
    .product-recommendation-form {
        /* Mobile styles */
    }
}
```

## 🧪 Testing Guidelines

### Manual Testing Checklist

- [ ] Plugin activates without errors
- [ ] Admin menu appears correctly
- [ ] Settings page loads and saves properly
- [ ] Form renders on frontend
- [ ] Questions can be added/edited/deleted
- [ ] Product characteristics can be assigned
- [ ] Recommendations work correctly
- [ ] Multi-language support functions
- [ ] Responsive design works on mobile
- [ ] AJAX functionality works
- [ ] No JavaScript errors in console
- [ ] No PHP errors in debug log

### Testing Environments

Test on these combinations:
- WordPress 5.0 + WooCommerce 5.0
- WordPress 6.0 + WooCommerce 6.0
- WordPress 6.4 + WooCommerce 8.0
- PHP 7.4, 8.0, 8.1, 8.2

### Browser Testing

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## 📚 Documentation

### Code Documentation

- **All functions** must have PHPDoc comments
- **All classes** must have class-level documentation
- **Complex logic** should have inline comments
- **Hooks and filters** should be documented

### User Documentation

- **README.md** should be updated for new features
- **Inline help** should be added to admin pages
- **Screenshots** should be updated for UI changes

## 🔄 Pull Request Process

### Before Submitting

1. **Test thoroughly** on multiple environments
2. **Follow coding standards** exactly
3. **Update documentation** if needed
4. **Check for conflicts** with main branch
5. **Ensure all tests pass** (if applicable)

### Pull Request Template

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Documentation update
- [ ] Code refactoring
- [ ] Performance improvement

## Testing
- [ ] Tested on WordPress 5.0+
- [ ] Tested on WooCommerce 5.0+
- [ ] Tested on PHP 7.4+
- [ ] Tested on multiple browsers
- [ ] No console errors
- [ ] No PHP errors

## Screenshots (if applicable)
Add screenshots here

## Checklist
- [ ] Code follows WordPress coding standards
- [ ] Security best practices followed
- [ ] Documentation updated
- [ ] No breaking changes
- [ ] Backward compatibility maintained
```

## 🏷️ Version Control

### Commit Messages

Use conventional commit format:
```
type(scope): description

feat(form): add new question type
fix(admin): resolve settings save issue
docs(readme): update installation instructions
style(css): improve mobile responsiveness
refactor(ajax): optimize recommendation algorithm
test(unit): add tests for new functionality
```

### Branch Naming

- `feature/feature-name` - New features
- `fix/bug-description` - Bug fixes
- `docs/documentation-update` - Documentation changes
- `refactor/code-improvement` - Code refactoring

## 🐛 Bug Reports

### Required Information

- **WordPress version**
- **WooCommerce version**
- **PHP version**
- **Plugin version**
- **Browser and version**
- **Steps to reproduce**
- **Expected vs actual behavior**
- **Error messages** (if any)
- **Screenshots** (if applicable)

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

## 💬 Communication

### Getting Help

- **GitHub Issues**: For bugs and feature requests
- **GitHub Discussions**: For questions and general discussion
- **Email**: For private or sensitive matters

### Code Reviews

- Be respectful and constructive
- Focus on the code, not the person
- Provide specific, actionable feedback
- Ask questions if something is unclear

## 📄 License

By contributing to this project, you agree that your contributions will be licensed under the same license as the project (GPL v2 or later).

## 🙏 Recognition

Contributors will be recognized in:
- **README.md** contributors section
- **Plugin changelog**
- **Release notes**

Thank you for contributing to the Product Recommendation System! 🎉 