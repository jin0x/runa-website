<article @php(post_class('h-entry max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:pt-40 md:pb-30'))>
  <header class="text-center mb-8">
    <div class="mb-4">
      @include('partials.entry-meta')
    </div>

    <h1 class="p-name text-3xl sm:text-4xl lg:text-5xl font-bold mb-6 pt-">
      {!! $title !!}
    </h1>
  </header>

  @if (has_post_thumbnail())
    <div class="mb-8 rounded-lg overflow-hidden">
      @php(the_post_thumbnail('full', ['class' => 'w-full h-auto']))
    </div>
  @endif

  <div class="e-content prose max-w-none">
    @php(the_content())
  </div>

  @if ($pagination)
    <footer class="mt-8">
      <nav class="page-nav" aria-label="Page">
        {!! $pagination !!}
      </nav>
    </footer>
  @endif

  <div class="mt-12">
    @php(comments_template())
  </div>
</article>
