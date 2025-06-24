---
title: Frontend Bundling with Vite
date: 2024-11-22
excerpt: Learn how Laravel integrates with Vite for modern frontend development.
image: /prezet/img/ogimages/laravel-vite.webp
author: bob
tags: [vite, frontend, assets, javascript]
---

Laravel integrates seamlessly with [Vite](https://vitejs.dev/) for an incredibly fast frontend development experience. Vite is a modern frontend build tool that provides an extremely fast development environment and bundles your code for production.

![Example image](frontend-bundling-with-vite.webp)

## Why Vite?

Vite offers significant advantages over older bundlers:

*   **Fast Cold Starts:** No more waiting long minutes for the dev server to spin up.
*   **Instant Hot Module Replacement (HMR):** Changes to your CSS and JS are reflected in the browser almost instantly without a full page reload.
*   **Optimized Production Builds:** Uses Rollup under the hood for highly optimized production bundles.

## Getting Started

Setting up Vite in a standard Laravel project is typically done during installation.

### Installation

Ensure you have Node.js and NPM (or Yarn) installed. Then, install the necessary frontend dependencies:

```bash
npm install
# or
yarn install
```

### Running the Development Server

Start the Vite development server to compile assets on the fly and enable HMR:

```bash
npm run dev
# or
yarn dev
```

This server will watch your `resources/css` and `resources/js` directories.

## Working with Assets

Vite needs to know which assets to bundle.

### Referencing Assets in Blade

Include your compiled CSS and JavaScript assets in your main Blade layout file (e.g., `app.blade.php`) using the `@vite` Blade directive. This directive handles linking to the correct assets whether the dev server is running or you're using production builds.

```blade
<head>
    ...
    <!-- Include CSS and JS entry points -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
```

### JavaScript & CSS

Your primary JavaScript file is usually `resources/js/app.js`, and CSS is typically in `resources/css/app.css`. Vite processes these files and any imports they contain.

### Static Assets

Images and fonts placed in `resources/` can be referenced relative to the source file and Vite will handle copying them to the build directory and rewriting URLs.

## Production Builds

When you're ready to deploy your application, you need to build the optimized production assets.

```bash
npm run build
# or
yarn build
```

Vite compiles and bundles your assets, placing them in the `public/build` directory. The `@vite` directive in your Blade templates will automatically point to these production assets when the development server isn't running. 
