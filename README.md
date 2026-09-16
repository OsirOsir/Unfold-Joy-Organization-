# Unfold Joy Organization – Custom WordPress Components

This repository contains custom **WordPress/PHP components** developed for the Unfold Joy Organization website.

The project extends WordPress with reusable, editable homepage sections that can be managed through the WordPress admin dashboard and displayed using shortcodes.

The custom components support content for organizational impact, community projects, calls to action, activities, and website updates.

## Features

- Custom WordPress plugin development
- Editable homepage content through the WordPress admin dashboard
- Reusable WordPress shortcodes
- Custom project and impact cards
- Community activities section
- Latest updates section
- Call-to-action section
- Configurable button labels and links
- WordPress media-library integration
- Editable images and section content
- Responsive layouts
- Custom CSS styling
- Input sanitization and WordPress security functions

## Main Components

### Impact / Projects Section

The `UJ Impact Projects Section` plugin provides an editable homepage section for highlighting organizational programs and projects.

Shortcode:

```text
[uj_impact_projects]
```

Administrators can configure:

- section title
- introductory text
- project images
- project titles
- project descriptions
- button labels
- project links
- section background
- spacing settings

### Home Sections Plugin

The `Unfold Joy Home Sections` component provides multiple homepage sections through a single shortcode.

Shortcode:

```text
[uj_home_sections]
```

The component includes:

- Impact / Projects
- Call to Action
- Community Activities
- Latest Updates

Content can be managed from the WordPress administration area without editing the PHP source code.

## Tech Stack

### Backend

- PHP
- WordPress
- WordPress Plugin API
- WordPress Settings API
- WordPress Options API
- WordPress Shortcode API

### Frontend

- HTML
- CSS
- JavaScript
- Responsive CSS Grid layouts

### WordPress Integration

The project uses WordPress functionality including:

- `add_action()`
- `add_shortcode()`
- `add_menu_page()`
- `register_setting()`
- `get_option()`
- WordPress Media Library
- WordPress sanitization functions
- WordPress escaping functions
- admin-enqueued scripts and styles

## Repository Structure

```text
Unfold-Joy-Organization/
├── README.md
├── uj-impact-projects-section.php
├── unfold-joy-home-sections.php
└── unfold-joy-home-sections1.php
```

### `uj-impact-projects-section.php`

Standalone plugin for the Impact / Projects section.

### `unfold-joy-home-sections.php`

Earlier implementation of the combined homepage-sections plugin.

### `unfold-joy-home-sections1.php`

Enhanced iteration of the homepage-sections plugin with additional configurable button labels, updated card styling, responsive improvements, and image guidance.

## Installation

### 1. Set up WordPress

Install and configure a WordPress development or staging environment.

### 2. Choose the required plugin component

Copy the required PHP plugin file into a WordPress plugin directory.

Example:

```text
wp-content/plugins/unfold-joy-home-sections/
```

### 3. Activate the plugin

Go to:

```text
WordPress Dashboard → Plugins
```

Activate the required custom plugin.

### 4. Configure the sections

Use the custom administration menu:

```text
UJ Home Sections
```

to configure section content, images, links, and labels.

### 5. Add the shortcode

Add the appropriate shortcode to a WordPress page.

For the complete homepage sections:

```text
[uj_home_sections]
```

For the standalone Impact / Projects component:

```text
[uj_impact_projects]
```

## Important Development Note

The repository currently contains two iterations of the `Unfold Joy Home Sections` plugin.

Both define the same WordPress plugin class and shortcode, so they should **not be activated together**.

For a production deployment, the intended current version should be retained and older iterations should be archived separately.

## Responsive Design

The custom sections include responsive styling for:

- desktop layouts
- tablet layouts
- mobile layouts
- responsive cards
- adaptive buttons
- flexible image presentation

## Content Management

The project was designed so that common homepage content can be changed from the WordPress dashboard without modifying source code.

Editable content includes:

- titles
- descriptions
- images
- buttons
- links
- activity details
- update labels
- call-to-action content

## Security Practices

The project uses WordPress sanitization and escaping functions for stored and rendered content.

Examples include:

- `sanitize_text_field()`
- `sanitize_textarea_field()`
- `esc_url_raw()`
- `esc_html()`
- `esc_url()`
- `esc_attr()`
- `wp_kses_post()`

Before production use, custom WordPress code should still be tested and reviewed within the target WordPress environment.

## Project Focus

This repository demonstrates experience with:

- custom WordPress plugin development
- PHP development
- WordPress administration interfaces
- shortcode development
- configurable CMS components
- responsive frontend design
- WordPress media integration
- settings management
- content sanitization and escaping
- iterative website development

## Development Note

This project includes AI-assisted development and iterative customization as part of the implementation process.

The final components were adapted for the requirements of the Unfold Joy Organization website.

## License

This project is maintained for development and portfolio purposes.
