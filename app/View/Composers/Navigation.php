<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use Log1x\Navi\Navi;

class Navigation extends Composer {
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.navigation',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with() {
        $data = $this->view->getData();
        $menu_location = $data['menu'] ?? 'primary_navigation';

        // Check if mega menu is enabled
        $mega_menu_enabled = get_field('enable_mega_menu', 'option');

        if ($mega_menu_enabled) {
            return [
                'navigation' => $this->buildMegaMenu(),
                'is_mega_menu' => true,
            ];
        }

        // Fallback to WordPress menu
        return [
            'navigation' => $this->navigation($menu_location),
            'is_mega_menu' => false,
        ];
    }

    /**
     * Returns the navigation for the specified menu location.
     *
     * @param string $menu_location
     * @return array|null
     */
    public function navigation($menu_location = 'primary_navigation') {
        $menu = Navi::make()->build($menu_location);

        if ($menu->isEmpty()) {
            return null;
        }

        return $menu->all();
    }

    /**
     * Build mega menu from ACF options.
     *
     * @return array|null
     */
    protected function buildMegaMenu() {
        $menu_items = get_field('header_menu_items', 'option');

        if (!$menu_items || empty($menu_items)) {
            return null;
        }

        return $this->formatMegaMenuItems($menu_items);
    }

    /**
     * Format ACF menu items into template-friendly structure.
     *
     * @param array $menu_items
     * @return array
     */
    protected function formatMegaMenuItems($menu_items) {
        $formatted = [];
        $current_url = rtrim(url()->current(), '/');

        foreach ($menu_items as $item) {
            $menu_item_link = $item['menu_item_link'] ?? [];

            // Skip if no link provided
            if (empty($menu_item_link['url'])) {
                continue;
            }

            $item_url = rtrim($menu_item_link['url'], '/');

            $formatted_item = [
                'label' => $menu_item_link['title'] ?? '',
                'url' => $menu_item_link['url'] ?? '#',
                'target' => $menu_item_link['target'] ?? '_self',
                'has_submenu' => $item['has_submenu'] ?? false,
                'submenu_groups' => [],
                'callout' => null,
                // Add compatibility properties for template
                'id' => 0, // No specific ID in ACF, set to 0 or generate if needed
                'active' => $current_url === $item_url,
                'classes' => '',
                'children' => null, // Will be populated if has_submenu is true
            ];

            // Process submenu groups if enabled
            if ($formatted_item['has_submenu'] && !empty($item['submenu_groups'])) {
                $formatted_item['submenu_groups'] = $this->formatSubmenuGroups($item    ['submenu_groups']);
                // Set children to true to trigger dropdown display
                $formatted_item['children'] = true;
            }

            // Process callout if enabled
            if ($formatted_item['has_submenu'] && 
                !empty($item['callout']) && 
                !empty($item['callout']['enable_callout'])) {
                $formatted_item['callout'] = $this->formatCallout($item['callout']);
            }

            $formatted[] = (object) $formatted_item;
        }

        return $formatted;
    }

    /**
     * Format submenu groups.
     *
     * @param array $groups
     * @return array
     */
    protected function formatSubmenuGroups($groups) {
        $formatted = [];

        foreach ($groups as $group) {
            $group_type = $group['group_type'] ?? 'regular';

            if ($group_type === 'regular') {
                $formatted[] = [
                    'type' => 'regular',
                    'title' => $group['group_title'] ?? '',
                    'icon' => $group['group_icon'] ?? null,
                    'items' => $this->formatSubmenuItems($group['submenu_items'] ?? []),
                ];
            } elseif ($group_type === 'featured') {
                $formatted[] = [
                    'type' => 'featured',
                    'title' => $group['featured_title'] ?? '',
                    'description' => $group['featured_description'] ?? '',
                    'background' => $group['featured_background'] ?? null,
                    'cta' => $group['featured_cta'] ?? null,
                ];
            }
        }

        return $formatted;
    }

    /**
     * Format submenu items (links).
     *
     * @param array $items
     * @return array
     */
    protected function formatSubmenuItems($items) {
        $formatted = [];

        foreach ($items as $item) {
            $link = $item['link'] ?? [];
            
            if (empty($link['url'])) {
                continue;
            }

            $formatted[] = [
                'label' => $link['title'] ?? '',
                'url' => $link['url'] ?? '#',
                'target' => $link['target'] ?? '_self',
                'description' => $item['description'] ?? '',
            ];
        }

        return $formatted;
    }

    /**
     * Format callout section.
     *
     * @param array $callout
     * @return array|null
     */
    protected function formatCallout($callout) {
        $cta = $callout['callout_cta'] ?? [];

        return [
            'title' => $callout['callout_title'] ?? '',
            'description' => $callout['callout_description'] ?? '',
            'background' => $callout['callout_background'] ?? null,
            'cta' => !empty($cta['url']) ? [
                'label' => $cta['title'] ?? '',
                'url' => $cta['url'] ?? '#',
                'target' => $cta['target'] ?? '_self',
            ] : null,
        ];
    }
}