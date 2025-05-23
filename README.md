# Official Blogging Template for Prezet

![prezet-blog-light](https://github.com/user-attachments/assets/7e8a1765-8584-4e9a-99f3-b23b1a75129d)
![prezet-blog-dark](https://github.com/user-attachments/assets/51007484-b2c0-40c7-858f-2c8f6e0f657d)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/prezet/blog-template.svg?style=flat-square)](https://packagist.org/packages/prezet/blog-template)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/prezet/blog-template/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/prezet/blog-template/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/prezet/blog-template/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/prezet/blog-template/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)

This package provides a blogging-focused starting template for the [Prezet Markdown Blogging Engine](https://github.com/benbjurstrom/prezet) [[1]](https://github.com/benbjurstrom/prezet). It sets up routes, controllers, views, CSS, and content structure suitable for a blog site.

The installer copies the necessary files into your Laravel application and then removes this package, leaving you with the template files to modify as needed.

## Installation

1.  **Require the package:**
    ```bash
    composer require prezet/blog-template --dev
    ```
    *(Note: We install as a dev dependency since the package removes itself after installation).*

2.  **Run the installer:**
    ```bash
    php artisan blog-template:install
    ```
    This command will:
    *   Copy routes, controllers, views, CSS, content stubs, and Vite configuration into your application.
    *   Install required Node dependencies (`tailwindcss`, `alpinejs`, etc.).
    *   Remove the `prezet/blog-template` package from your Composer dependencies.

## Files Added/Modified

The `blog-template:install` command will add or modify the following files and directories within your Laravel application:

```
your-laravel-app/
├── app/
│   └── Http/
│       └── Controllers/
│           └── Prezet/     # Contains the index, show, image, ogimage, and search controllers
├── routes/
│   ├── prezet.php          # Route definitions for the above controllers
│   └── web.php             # Modified to include routes/prezet.php
├── resources/
│   ├── css/
│   │   └── prezet.css      # Contains the Tailwind v4 CSS
│   └── views/
│       ├── components/
│       │   └── prezet/     # A collection of Blade components used in the template
│       └── prezet/         # Page level Blade views for the index, show, and ogimage routes
├── prezet/                 # Example content containng markdown files and images
├── package.json            # Modified with added node dependencies
└── vite.config.js          # Overwrites existing vite.config.js. Be sure to check this file.
```

## Testing

```bash
composer test
```

## Credits

- [Ben Bjurstrom](https://github.com/benbjurstrom)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
