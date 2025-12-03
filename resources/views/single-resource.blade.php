@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
@endphp

@extends('layouts.app')

@section('content')
  @while(have_posts()) 
    @php the_post() @endphp
        {{-- Page Content --}}
        <div class="max-w-none">
          @php(the_content())
        </div>
  @endwhile
@endsection