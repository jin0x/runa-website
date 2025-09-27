# Asset Loading Issues Investigation & Resolution Attempts

## 🔍 Problem Summary

**Primary Issue**: Assets (fonts and JavaScript) load correctly on local HTTP (`runa.local`) but fail on HTTPS and staging environments (`runa.flywheelsites.com`) while CSS loads fine across all environments.

**Environment Matrix**:
```
┌─────────────────────────┬─────────┬─────────┬─────────────┐
│ Environment             │ CSS     │ Fonts   │ JavaScript  │
├─────────────────────────┼─────────┼─────────┼─────────────┤
│ runa.local (HTTP)       │ ✅ Works│ ✅ Works│ ✅ Works    │
│ runa.local (HTTPS)      │ ✅ Works│ ❌ Fails│ ❌ Fails    │
│ runa.flywheelsites.com  │ ✅ Works│ ❌ Fails│ ❌ Fails    │
│ Future: runa.io/com     │ ❓ TBD  │ ❓ TBD  │ ❓ TBD      │
└─────────────────────────┴─────────┴─────────┴─────────────┘
```

## 🏗️ Technical Stack

- **WordPress Theme**: Roots Sage
- **Framework**: Laravel Acorn 5.0.0-beta.2 (Laravel 11.34.2)
- **Build Tool**: Vite 6.2.0 with Laravel Vite plugin
- **Dependencies**: Alpine.js, GSAP, Swiper, Turbo
- **PHP**: 8.2.23

## 📊 Root Cause Analysis Flow

```mermaid
graph TD
    A[Asset Loading Issues] --> B{yarn dev running?}
    B -->|Yes| C[Assets work fine]
    B -->|No| D[Assets fail on HTTPS/staging]
    D --> E[Initial hypothesis: Protocol mismatch]
    E --> F[Tested protocol-relative URLs]
    F --> G[Still failing]
    G --> H[User provides error logs]
    H --> I[Discovery: Acorn 5.0.0-beta.2 bug]
    I --> J[@vite directive fails with type error]
    J --> K[Vite::asset() also affected]
    K --> L[Decision: Bypass Acorn Vite integration]
```

## 🚨 Critical Discovery: Acorn Beta Bug

### Error Details
```php
TypeError: normalizeRelativePath(): Argument #1 ($path) must be of type string, array given
```

**Location**: Acorn's Vite integration in `@vite` Blade directive and `Vite::asset()` function

**Trigger**: Only occurs when `yarn dev` is NOT running (production/staging builds)

**Impact**:
- Fonts fail to load → FOUC (Flash of Unstyled Content)
- JavaScript fails to load → Broken interactivity
- CSS loads fine (different code path)

## 🔧 Attempted Solutions

### 1. Protocol-Relative URLs (❌ Failed)
**Hypothesis**: Mixed content issues between HTTP/HTTPS
**Implementation**: Modified asset URLs to use `//` protocol
**Result**: No improvement, root cause was deeper

### 2. Acorn Version Upgrade (❌ Failed)
**Attempt**: Upgrade from 5.0.0-beta.2 to 5.0.5 stable
**Blocker**: Laravel 11 dependency conflicts
```bash
composer require roots/acorn:^5.0.5
# Failed: Your requirements could not be resolved to an installable set of packages
```

### 3. Complete Acorn Vite Bypass (✅ Implemented)
**Approach**: Replace `@vite` directive with manual asset loading
**Implementation**: Custom helper functions + template modifications

## 🛠️ Current Implementation

### Architecture Overview
```
┌─────────────────────────────────────────────────────────────┐
│                    Asset Loading Flow                        │
├─────────────────────────────────────────────────────────────┤
│ 1. Vite builds assets → manifest.json                      │
│ 2. Custom helpers read manifest.json                       │
│ 3. Template manually includes assets                       │
│ 4. Bypasses buggy Acorn @vite integration                  │
└─────────────────────────────────────────────────────────────┘
```

### File Structure & Changes

#### 1. `app/helpers.php` - Custom Asset Loading Functions
```php
/**
 * Get asset URL from manifest.json
 * Bypass for Acorn beta Vite::asset() bug
 */
function get_vite_asset($resource_path) {
    static $manifest = null;

    // Defensive check for WordPress functions
    if (!function_exists('get_template_directory')) {
        return '';
    }

    // Load manifest once per request
    if ($manifest === null) {
        $manifest_path = get_template_directory() . '/public/build/manifest.json';
        $manifest = file_exists($manifest_path)
            ? json_decode(file_get_contents($manifest_path), true)
            : [];
    }

    // Return hashed filename from manifest
    if (isset($manifest[$resource_path]['file'])) {
        return get_template_directory_uri() . '/public/build/' . $manifest[$resource_path]['file'];
    }

    // Fallback to direct path
    return get_template_directory_uri() . '/public/build/' . $resource_path;
}

/**
 * Get entry point with associated CSS chunks
 * Critical for JavaScript entries that generate CSS
 */
function get_vite_entry_with_css($entry_path) {
    // Similar manifest loading logic
    // Returns: ['js' => 'path/to/js', 'css' => ['array', 'of', 'css', 'chunks']]
}
```

#### 2. `resources/views/layouts/app.blade.php` - Manual Asset Inclusion
```blade
{{-- BEFORE: Buggy Acorn integration --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])

{{-- AFTER: Manual bypass --}}
@php
$mainCssUrl = \App\Helpers\get_vite_asset('resources/css/app.css');
$jsAssets = \App\Helpers\get_vite_entry_with_css('resources/js/app.js');
@endphp

{{-- Main CSS file --}}
@if($mainCssUrl)
<link rel="stylesheet" href="{{ $mainCssUrl }}">
@endif

{{-- CSS chunks from JS entries (critical for component styles) --}}
@if($jsAssets['css'])
@foreach($jsAssets['css'] as $cssChunk)
<link rel="stylesheet" href="{{ $cssChunk }}">
@endforeach
@endif

{{-- JavaScript file --}}
@if($jsAssets['js'])
<script src="{{ $jsAssets['js'] }}" defer></script>
@endif
```

#### 3. `functions.php` - Font Loading Fix
```php
/**
 * Add custom @font-face styles using bypass helpers
 * Prevents FOUC (Flash of Unstyled Content)
 */
add_action('wp_head', function () {
    echo "<style>
        @font-face {
            font-family: 'Lineca';
            src: url('" . \App\Helpers\get_vite_asset('resources/fonts/Lineca-Regular.woff2') . "') format('woff2'),
                url('" . \App\Helpers\get_vite_asset('resources/fonts/Lineca-Regular.woff') . "') format('woff');
            font-weight: 400;
            font-style: normal;
        }
        /* Additional font-face declarations */
    </style>";
}, 100);
```

## 📋 Current Status

### ✅ Resolved Issues
- [x] Acorn beta `@vite` directive bypass implemented
- [x] Custom asset loading functions created
- [x] Font loading fixed (prevents FOUC)
- [x] CSS chunks handling for JavaScript entries
- [x] Environment-specific builds working
- [x] Staging build completed successfully

### ⚠️ Recent Issues Encountered
- **Blade Template Syntax Error**: Fixed by simplifying PHP blocks and adding defensive checks
- **CSS Chunks Missing**: Resolved with enhanced `get_vite_entry_with_css()` function

### 🔧 Environment-Specific Build Process
```bash
# Local development
npm run dev  # Vite dev server

# Local production test
npm run build  # Uses current environment

# Staging deployment
APP_URL=https://runa.flywheelsites.com npm run build

# Future production
APP_URL=https://runa.io npm run build  # or runa.com
```

## 🎯 Outstanding Questions & Unknowns

### 1. Acorn Beta Stability
- **Question**: When will Acorn 5.0.x stable be Laravel 11 compatible?
- **Impact**: Current bypass is a workaround, not a permanent solution
- **Risk**: Other Acorn features might have similar beta bugs

### 2. CSS Chunks Complexity
- **Question**: Are we loading all necessary CSS chunks?
- **Context**: JavaScript entries can generate multiple CSS files
- **Example**: `resources/js/app.js` → `["assets/app-Ds_YmOBQ.css"]`
- **Risk**: Missing component-specific styles

### 3. Build Performance
- **Question**: Impact of bypassing Acorn's optimizations?
- **Context**: Acorn provides additional Vite integration features
- **Unknown**: What optimizations are we losing?

### 4. Future Scalability
- **Question**: How will this approach handle:
  - Multiple entry points?
  - Dynamic imports?
  - Code splitting?
- **Current**: Single entry point works fine
- **Risk**: May need enhancement for complex applications

## 🚧 Things NOT Yet Tried

### 1. Alternative Acorn Versions
```bash
# Could attempt other beta versions
composer require roots/acorn:5.0.0-beta.1
composer require roots/acorn:5.0.0-beta.3
```

### 2. Laravel Vite Plugin Alternatives
- Direct Vite integration without Laravel plugin
- Custom Vite plugin for WordPress
- Rollup-based build system

### 3. Acorn Feature Flags
- Investigate if Vite integration can be disabled
- Check for configuration options to use alternative asset loading

### 4. WordPress Core Integration
- Native WordPress asset enqueueing with Vite
- `wp_enqueue_script()` + `wp_enqueue_style()` approach

## 🐛 Known Potential Issues

### 1. Manifest Loading Race Conditions
- **Risk**: `manifest.json` read before generation complete
- **Mitigation**: File existence checks implemented
- **Monitor**: Build timing issues

### 2. Cache Invalidation
- **Risk**: Browser caching old manifest references
- **Current**: Vite handles with hash-based filenames
- **Monitor**: Asset versioning consistency

### 3. Error Handling Gaps
- **Risk**: Silent failures if helpers fail
- **Current**: Defensive checks added
- **Improve**: Add logging/debugging capabilities

## 📝 Recommendations for Next Developer

### Immediate Actions
1. **Test thoroughly** on all environments after syntax fix
2. **Monitor browser console** for any remaining asset errors
3. **Verify testimonials slider** styling is now correct

### Short-term Considerations
1. **Add error logging** to asset helper functions
2. **Create fallback** asset loading for manifest failures
3. **Document** any new components that need CSS chunks

### Long-term Strategy
1. **Monitor Acorn releases** for Laravel 11 compatibility
2. **Plan migration** back to native Acorn Vite integration
3. **Consider** alternative WordPress build tools if Acorn remains problematic

### Development Workflow
1. **Local development**: Continue using `npm run dev`
2. **Staging deployment**: Use `APP_URL=https://runa.flywheelsites.com npm run build`
3. **Production**: Will need `APP_URL=https://runa.io npm run build`

## 📊 Asset Loading Verification Checklist

```bash
# Verify build assets exist
ls -la public/build/assets/

# Check manifest structure
cat public/build/manifest.json | jq '."resources/js/app.js"'

# Verify helper functions work
wp eval 'echo \App\Helpers\get_vite_asset("resources/css/app.css");'

# Test CSS chunks loading
wp eval 'print_r(\App\Helpers\get_vite_entry_with_css("resources/js/app.js"));'
```

## 🔗 Critical Files to Monitor

1. **`public/build/manifest.json`** - Asset mappings and CSS chunks
2. **`app/helpers.php`** - Custom asset loading logic
3. **`resources/views/layouts/app.blade.php`** - Manual asset inclusion
4. **`functions.php`** - Font loading and WordPress integration
5. **`vite.config.js`** - Build configuration and environment handling

---

**Last Updated**: $(date)
**Status**: Workaround implemented, testing needed
**Next Steps**: Verify fix resolves all environment issues