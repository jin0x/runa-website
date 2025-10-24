@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @include('partials.page-header')

    @if(\App\Helpers\has_acf_blocks())
      @php(the_content())
    @else
      <div class="w-full max-w-[80ch] mx-auto px-6 lg:px-8 py-32 prose [&_ul]:!my-2 [&_ol]:!my-2 [&_li]:!my-1 [&_p]:!my-3 [&_h1]:!mt-4 [&_h1]:!mb-2 [&_h2]:!mt-4 [&_h2]:!mb-2 [&_h3]:!mt-4 [&_h3]:!mb-2 [&_h4]:!mt-4 [&_h4]:!mb-2 [&_h5]:!mt-4 [&_h5]:!mb-2 [&_h6]:!mt-4 [&_h6]:!mb-2">
        @php(the_content())
      </div>
    @endif
  @endwhile
@endsection
