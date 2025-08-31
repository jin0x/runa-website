@php
  use App\Enums\TextSize;
  use App\Enums\TextTag;
@endphp

@props([
    'code' => '', // Code content
    'language' => 'text', // Programming language
    'filename' => '', // Optional filename
    'copyable' => true, // Show copy button
    'lineNumbers' => false, // Show line numbers
    'highlight' => [], // Array of line numbers to highlight
    'maxHeight' => null, // Max height with scroll
    'class' => '',
])

@php
  // Generate unique ID for this code block
  $codeId = 'code-' . uniqid();
  
  // Split code into lines for line numbers
  $lines = explode("\n", rtrim($code));
  $totalLines = count($lines);

  // Language label for display
  $languageLabel = match($language) {
      'js' => 'JavaScript',
      'ts' => 'TypeScript',
      'jsx' => 'React JSX',
      'tsx' => 'React TSX',
      'php' => 'PHP',
      'html' => 'HTML',
      'css' => 'CSS',
      'scss' => 'SCSS',
      'json' => 'JSON',
      'yaml' => 'YAML',
      'yml' => 'YAML',
      'md' => 'Markdown',
      'bash' => 'Bash',
      'sh' => 'Shell',
      'sql' => 'SQL',
      'python' => 'Python',
      'py' => 'Python',
      default => strtoupper($language),
  };
@endphp

<div class="relative bg-neutral-900 rounded-lg overflow-hidden {{ $class }}">
  {{-- Header --}}
  @if($filename || $copyable || $language !== 'text')
    <div class="flex items-center justify-between px-4 py-3 bg-neutral-800 border-b border-neutral-700">
      <div class="flex items-center space-x-3">
        @if($filename)
          <x-text
            :as="TextTag::SPAN"
            :size="TextSize::SMALL"
            class="text-neutral-300 font-mono"
          >
            {{ $filename }}
          </x-text>
        @endif

        @if($language !== 'text')
          <x-badge variant="secondary" size="sm">
            {{ $languageLabel }}
          </x-badge>
        @endif
      </div>

      @if($copyable)
        <button
          x-data="{
            copied: false,
            copy() {
              const code = document.getElementById('{{ $codeId }}').textContent;
              navigator.clipboard.writeText(code).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
              });
            }
          }"
          @click="copy()"
          class="flex items-center space-x-2 px-3 py-1 bg-neutral-700 hover:bg-neutral-600 rounded-md transition-colors duration-200 text-neutral-300 hover:text-white"
          type="button"
        >
          <svg x-show="!copied" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
          </svg>
          <svg x-show="copied" class="w-4 h-4 text-semantic-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          
          <x-text
            :as="TextTag::SPAN"
            :size="TextSize::XSMALL"
            class="font-medium"
          >
            <span x-show="!copied">Copy</span>
            <span x-show="copied" class="text-semantic-success" style="display: none;">Copied!</span>
          </x-text>
        </button>
      @endif
    </div>
  @endif

  {{-- Code Content --}}
  <div class="relative {{ $maxHeight ? 'overflow-auto' : '' }}" @if($maxHeight) style="max-height: {{ $maxHeight }}" @endif>
    <div class="flex">
      {{-- Line Numbers --}}
      @if($lineNumbers)
        <div class="flex-shrink-0 px-4 py-4 bg-neutral-800 border-r border-neutral-700 select-none">
          @foreach($lines as $index => $line)
            @php
              $lineNumber = $index + 1;
              $isHighlighted = in_array($lineNumber, $highlight);
            @endphp
            <div class="text-neutral-500 text-sm font-mono leading-6 {{ $isHighlighted ? 'bg-brand-primary/20' : '' }}">
              {{ $lineNumber }}
            </div>
          @endforeach
        </div>
      @endif

      {{-- Code --}}
      <div class="flex-1 overflow-x-auto">
        <pre id="{{ $codeId }}" class="p-4 text-neutral-100 text-sm font-mono leading-6 whitespace-pre"><code class="language-{{ $language }}">{{ $code }}</code></pre>
      </div>
    </div>

    {{-- Highlighted Lines Overlay --}}
    @if(!empty($highlight) && !$lineNumbers)
      <div class="absolute inset-0 pointer-events-none">
        @foreach($lines as $index => $line)
          @php
            $lineNumber = $index + 1;
            $isHighlighted = in_array($lineNumber, $highlight);
          @endphp
          @if($isHighlighted)
            <div 
              class="absolute left-0 right-0 bg-brand-primary/10 border-l-4 border-brand-primary"
              style="top: {{ ($index * 1.5) + 1 }}rem; height: 1.5rem;"
            ></div>
          @endif
        @endforeach
      </div>
    @endif
  </div>
</div>

{{-- Optional: Load Prism.js for syntax highlighting --}}
@push('scripts')
<script>
  // Basic syntax highlighting if Prism.js is not loaded
  if (typeof Prism === 'undefined') {
    // Simple fallback highlighting could go here
    console.log('Code block rendered without syntax highlighting. Consider loading Prism.js for enhanced highlighting.');
  }
</script>
@endpush