# Vite Manifest Parsing Bug in Acorn 5.0 Beta

## The Problem

When accessing any page with Gutenberg blocks (both locally and on staging), the site crashed with:

```
TypeError: Roots\Acorn\Assets\Manifest::normalizeRelativePath():
Argument #1 ($path) must be of type string, array given
```

On staging, this was compounded by a secondary error where `highlight_file()` was disabled, causing the error handler itself to crash.

## Root Cause Analysis

### What Was Happening

1. **The Trigger**: In `app/setup.php`, we had code that loaded editor assets using Acorn's Vite facade:

```php
add_filter('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});
```

This runs **every time you load the Gutenberg block editor** (e.g., editing a page with ACF blocks), which triggers the manifest parsing bug.

**Important Note About Local vs Production**:
- **Local environment**: The bug happens on **Gutenberg editor pages only** because:
  - The frontend has a broken conditional in `resources/views/layouts/app.blade.php`:
    ```php
    @if(env('APP_ENVIRONMENT', 'production') === 'development')
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    ```
    This compares `'production'` (the fallback) to `'development'`, which is **always false**, so frontend never uses Vite facade
  - However, the Gutenberg editor **still crashes** because it uses `Vite::withEntryPoints()` via the `admin_head` hook above

- **Production/Staging**: Same issue - frontend doesn't crash (broken conditional), but **Gutenberg editor always crashes** when loading editor assets

2. **The Vite Manifest Structure**: When Vite builds assets, it generates `public/build/manifest.json` with entries like this:

```json
{
  "resources/js/app.js": {
    "file": "assets/app-BMd24Smn.js",
    "name": "app",
    "src": "resources/js/app.js",
    "isEntry": true,
    "css": [
      "assets/app-Ds_YmOBQ.css"
    ]
  }
}
```

Notice the **`"css"` property is an ARRAY** of CSS files that are associated with this JavaScript entry point.

3. **Acorn's Manifest Parser Bug**: The `Manifest` class in Acorn 5.0 beta has this constructor:

```php
public function __construct(string $path, string $uri, array $assets = [], ?array $bundles = null)
{
    $this->path = $path;
    $this->uri = $uri;
    $this->bundles = $bundles;

    foreach ($assets as $original => $revved) {
        $this->assets[$this->normalizeRelativePath($original)] =
            $this->normalizeRelativePath($revved);  // ← BUG HERE
    }
}

protected function normalizeRelativePath(string $path): string
{
    $path = str_replace('\\', '/', $path);
    $path = preg_replace('%//+%', '/', $path);
    return ltrim($path, './');
}
```

**The Bug**: When iterating through `$assets`, it assumes every value (`$revved`) is a **string**, but in Vite's manifest, some values are **nested objects/arrays** (like `"css": ["file.css"]`). When the loop encounters the `css` array, it tries to pass `["assets/app-Ds_YmOBQ.css"]` to `normalizeRelativePath()`, which expects a string.

### Why It Only Failed Sometimes

- **Development with `yarn dev`**: When Vite dev server is running, Acorn uses hot module replacement and doesn't parse the build manifest the same way
- **Production builds**: The manifest is parsed directly, triggering the bug
- **Gutenberg editor pages**: Our editor asset loading (`Vite::withEntryPoints()`) triggered manifest parsing on every block editor page load

## The Challenge

We couldn't:
1. Upgrade Acorn (still in beta, breaking changes possible)
2. Modify the Vite manifest structure (it's a standard format)
3. Remove the CSS array (needed for proper asset loading)

## The Fix We Implemented

### Original Workaround (Broken Conditional)

Initially, to prevent crashes on Flywheel hosting, we added a broken conditional in `resources/views/layouts/app.blade.php`:

```php
@if(env('APP_ENVIRONMENT', 'production') === 'development')
  @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
```

This condition **always evaluates to false** because it compares `'production'` (the default fallback value) to `'development'`, effectively disabling the `@vite()` directive entirely. This prevented frontend crashes but meant **no assets were loading**.

### Current Solution (Manual Asset Enqueuing)

We **bypassed Acorn's Vite facade entirely** by implementing direct manifest parsing that correctly handles arrays and enqueuing assets manually through WordPress hooks:

### 1. Created Helper Functions (`app/helpers.php`)

```php
function get_vite_entry_with_css($entry_path)
{
    $manifest = json_decode(file_get_contents($manifest_path), true);
    $entry = $manifest[$entry_path];

    $result = ['js' => '', 'css' => []];

    if (isset($entry['file'])) {
        $result['js'] = $base_url . $entry['file'];
    }

    // Correctly handle CSS array
    if (isset($entry['css']) && is_array($entry['css'])) {
        foreach ($entry['css'] as $css_file) {
            $result['css'][] = $base_url . $css_file;
        }
    }

    return $result;
}
```

This function:
- Reads the manifest directly
- Checks if `css` is an array before iterating
- Returns both JS and CSS files separately

### 2. Frontend Assets (`app/setup.php`)

```php
add_action('wp_enqueue_scripts', function () {
    enqueue_vite_assets(); // Uses get_vite_entry_with_css() internally
});
```

### 3. Editor Assets (`app/setup.php`)

```php
add_filter('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    enqueue_editor_assets(); // Direct manifest parsing, no Vite facade
});
```

## The Future Fix

### What Acorn Needs to Do

The `Manifest` class needs to handle nested arrays/objects in the Vite manifest. The fix would look like:

```php
public function __construct(string $path, string $uri, array $assets = [], ?array $bundles = null)
{
    $this->path = $path;
    $this->uri = $uri;
    $this->bundles = $bundles;

    foreach ($assets as $original => $revved) {
        // Skip non-string values (nested objects/arrays)
        if (!is_string($original) || !is_string($revved)) {
            continue;
        }

        $this->assets[$this->normalizeRelativePath($original)] =
            $this->normalizeRelativePath($revved);
    }
}
```

Or better yet, implement proper Vite manifest parsing that understands the full structure:

```php
public function __construct(string $path, string $uri, array $manifest = [], ?array $bundles = null)
{
    $this->path = $path;
    $this->uri = $uri;
    $this->bundles = $bundles;

    foreach ($manifest as $key => $entry) {
        if (is_array($entry)) {
            // Handle Vite manifest entries
            if (isset($entry['file'])) {
                $this->assets[$key] = $entry['file'];
            }
            // Handle associated CSS
            if (isset($entry['css']) && is_array($entry['css'])) {
                foreach ($entry['css'] as $css) {
                    $this->cssChunks[$key][] = $css;
                }
            }
        } else {
            // Legacy string-based manifest
            $this->assets[$key] = $entry;
        }
    }
}
```

### When Can We Revert to Using Acorn's Vite Facade?

Once Acorn releases a stable version (likely 5.1+) with proper Vite manifest support, we can migrate back to using the Vite facade.

#### Migration Steps

1. **Update Acorn version**
   ```bash
   # In composer.json, update:
   "roots/acorn": "^5.1"

   # Then run:
   composer update roots/acorn
   ```

2. **Revert `app/setup.php` editor assets**

   **BEFORE (current workaround):**
   ```php
   use function App\Helpers\enqueue_editor_assets;

   add_filter('admin_head', function () {
       if (! get_current_screen()?->is_block_editor()) {
           return;
       }

       enqueue_editor_assets();
   });
   ```

   **AFTER (using Acorn's Vite facade):**
   ```php
   use Illuminate\Support\Facades\Vite;

   add_filter('admin_head', function () {
       if (! get_current_screen()?->is_block_editor()) {
           return;
       }

       $dependencies = json_decode(Vite::content('editor.deps.json'));

       foreach ($dependencies as $dependency) {
           if (! wp_script_is($dependency)) {
               wp_enqueue_script($dependency);
           }
       }

       echo Vite::withEntryPoints([
           'resources/js/editor.js',
       ])->toHtml();
   });
   ```

3. **Revert `app/setup.php` frontend assets**

   **BEFORE (current workaround):**
   ```php
   use function App\Helpers\enqueue_vite_assets;
   use function App\Helpers\get_vite_asset;

   add_action('wp_head', function () {
       echo "<style>
           @font-face {
               font-family: 'Lineca';
               src: url('" . get_vite_asset('resources/fonts/Lineca-Regular.woff2') . "') format('woff2');
               font-weight: 400;
           }
       </style>";
   }, 1);

   add_action('wp_enqueue_scripts', function () {
       enqueue_vite_assets();
   });
   ```

   **AFTER (using Acorn's Vite facade):**
   ```php
   use Illuminate\Support\Facades\Vite;

   add_action('wp_head', function () {
       echo "<style>
           @font-face {
               font-family: 'Lineca';
               src: url('" . Vite::asset('resources/fonts/Lineca-Regular.woff2') . "') format('woff2');
               font-weight: 400;
           }
       </style>";
   }, 1);

   add_action('wp_enqueue_scripts', function () {
       echo Vite::withEntryPoints([
           'resources/css/app.css',
           'resources/js/app.js',
       ])->toHtml();
   });
   ```

4. **Re-enable `@vite()` directive in `resources/views/layouts/app.blade.php`**

   **BEFORE (broken conditional workaround):**
   ```php
   @if(env('APP_ENVIRONMENT', 'production') === 'development')
     @vite(['resources/css/app.css', 'resources/js/app.js'])
   @endif
   ```

   **AFTER (properly working):**
   ```php
   @vite(['resources/css/app.css', 'resources/js/app.js'])
   ```

   Simply remove the broken conditional and let the `@vite()` directive work directly.

5. **Optional: Clean up helpers (if no longer needed)**

   If nothing else uses `get_vite_asset()`, `get_vite_entry_with_css()`, `enqueue_vite_assets()`, or `enqueue_editor_assets()` from `app/helpers.php`, you can remove them.

6. **Test thoroughly**
   - Test frontend pages (local and staging)
   - Test Gutenberg editor with ACF blocks
   - Verify CSS and JS are loading correctly
   - Check browser console for errors

### How to Check If It's Fixed

```bash
# Check Acorn version
composer show roots/acorn

# Look for this file in vendor
cat vendor/roots/acorn/src/Roots/Acorn/Assets/Manifest.php

# Search for array handling in the constructor
grep -A 10 "foreach.*assets" vendor/roots/acorn/src/Roots/Acorn/Assets/Manifest.php
```

If you see type checking or array handling for `$revved`, the bug is likely fixed.

## Summary

- **Problem**: Acorn 5.0 beta's `Manifest` class can't parse Vite's manifest format with CSS arrays
- **Impact**: Crashed on all Gutenberg editor pages and production builds
- **Fix**: Bypass Acorn's Vite facade, parse manifest manually with proper array handling
- **Future**: Wait for stable Acorn 5.1+ release with proper Vite manifest support
- **Files Modified**:
  - `app/helpers.php` - Added `get_vite_entry_with_css()` and `enqueue_editor_assets()`
  - `app/setup.php` - Replaced Vite facade calls with direct helper functions
