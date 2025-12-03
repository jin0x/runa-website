@php
  use App\Enums\HeadingTag;
  use App\Enums\HeadingSize;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
@endphp

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    <article class="press-release-single">
      {{-- Main Content Container with 64px padding --}}
      <div class="px-16 pt-32 pb-16 max-w-[1250px] mx-auto">
        
        {{-- Centered Title --}}
        <header class="text-center mb-12">
          <x-heading
            :as="HeadingTag::H1"
            :size="HeadingSize::HERO"
            class="text-center"
          >
            {!! get_the_title() !!}
          </x-heading>
        </header>

        {{-- Date (left aligned) --}}
        <div class="mb-6">
          <x-text
            :as="TextTag::SPAN"
            :size="TextSize::CAPTION"
            :color="TextColor::GRAY"
            class="font-medium"
          >
            {{ get_the_date('M j, Y g:i A') }}
          </x-text>
        </div>

        {{-- Page Content --}}
        <div class="prose max-w-none">
          @php(the_content())
        </div>

      </div>
    </article>
  @endwhile
@endsection