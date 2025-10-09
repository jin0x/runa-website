@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @include('partials.page-header')

    @if(\App\Helpers\has_acf_blocks())
      @includeFirst(['partials.content-page', 'partials.content'])
    @else
      <x-container classes="py-24 lg:py-32 prose prose-xl">
        @includeFirst(['partials.content-page', 'partials.content'])
      </x-container>
    @endif
  @endwhile
@endsection
