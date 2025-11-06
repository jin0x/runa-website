@php
  use App\Enums\HeadingSize;
  use App\Enums\HeadingTag;
  use App\Enums\TextTag;
  use App\Enums\TextSize;
  use App\Enums\TextColor;
  use App\Enums\ButtonVariant;
  use App\Enums\ButtonType;
  use App\Enums\ButtonSize;
  use function App\Helpers\apply_tailwind_classes_to_content;

  // Global context
  $postId = $post ?? get_the_ID();

  // Category badge
  $caseStudyCategories = get_the_terms($postId, 'case_study_category');
  $primaryCategory     = $caseStudyCategories[0] ?? null;

  // Hero description
  $heroDescription = get_field('hero_description') ?? '';

  // Primary CTA | PDF
  $caseStudyFile = get_field('case_study_pdf');
  $caseStudyPdfUrl = is_array($caseStudyFile)
      ? ($caseStudyFile['url'] ?? '')
      : (is_string($caseStudyFile) ? $caseStudyFile : '');
  $caseStudyPdfUrl = $caseStudyPdfUrl ? esc_url($caseStudyPdfUrl) : '';
  $ctaText = get_field('cta_text') ?? 'Download Case Study';

  // Hero image fallback
  $heroImageHtml = '';
  $heroImageField = get_field('hero_image');

  if ($heroImageField) {
      $heroImageHtml = wp_get_attachment_image(
          $heroImageField['ID'],
          'full',
          false,
          [
              'class' => 'w-full h-auto',
              'loading' => 'lazy',
          ]
      );
  } elseif (has_post_thumbnail($postId)) {
      $heroImageHtml = get_the_post_thumbnail(
          $postId,
          'full',
          [
              'class' => 'w-full h-auto',
              'loading' => 'lazy',
          ]
      );
  }

  // Left Content
  $case_study_left_content_items = get_field('case_study_left_content_items');

  // Right Content | Sidebar
  $resultHeading = get_field('results_section_heading') ? get_field('results_section_heading') : 'Results';
  $resultMetrics = get_field('results');

  // Bottom Content
  $case_study_bottom_content_items = get_field('case_study_bottom_content_items');

  // Testimonial
  $testimonialReference = get_field('testimonial_reference');

  // Success Points
  $successPointsHeading = get_field('success_points_section_heading') ? get_field('success_points_section_heading') : 'Why partners trust Runa for long-term growth';
  $successPoints        = get_field('success_points');
@endphp

<article @php(post_class('case-study-single max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:pt-40 md:pb-30'))>
  <header class="text-center flex flex-col space-y-10">
    @if($primaryCategory)
      <div>
        <x-badge variant="default" size="sm" rounded="full"
          class="bg-primary-green-neon text-primary-dark">
          {{ $primaryCategory->name }}
        </x-badge>
      </div>
    @endif

    <x-heading
      :as="HeadingTag::H1"
      :size="HeadingSize::HERO"
    >
      {{ $title }}
    </x-heading>

    @if($heroDescription)
      <x-text
        :as="TextTag::P"
        :size="TextSize::LARGE"
        :color="TextColor::LIGHT"
      >
        {{ $heroDescription }}
      </x-text>
    @endif

    @if($caseStudyPdfUrl)
      <div class="pt-2">
        <x-button
          :variant="ButtonVariant::PRIMARY"
          :as="ButtonType::LINK"
          :size="ButtonSize::DEFAULT"
          :href="$caseStudyPdfUrl"
          :icon="true"
          iconPosition="right"
          target="_blank"
          rel="noopener"
        >
          {{ $ctaText }}
        </x-button>
      </div>
    @endif

    @if ($heroImageHtml)
      <div class="mb-16 rounded-lg overflow-hidden">
        {!! $heroImageHtml !!}
      </div>
    @endif
  </header>

  <div class="single-case-content grid grid-cols-1 {{ $resultMetrics ? 'md:grid-cols-2 xl:grid-cols-[6.7fr_3.3fr]' : '' }} gap-x-24">
    {{-- Left Content --}}
    <div class="space-y-10">
      @if($case_study_left_content_items)
        @foreach($case_study_left_content_items as $left_item)
          <div class="space-y-6">
            <x-heading
              :as="HeadingTag::H2"
              :size="HeadingSize::H3"
            >
              {{ $left_item['content_title'] }}
            </x-heading>
            @if($left_item['content_description'])
              <div>
                {!! apply_tailwind_classes_to_content($left_item['content_description']) !!}
              </div>
            @endif
          </div>
        @endforeach
      @endif
    </div>

    {{-- Sidebar --}}
    @if($resultMetrics)
      <div class="sidebar">
        <div class="space-y-6 sticky top-10">
          <x-heading
            :as="HeadingTag::H2"
            :size="HeadingSize::H3"
            :color="TextColor::GRAY"
          >
            {{ $resultHeading }}
          </x-heading>
            <ul class="space-y-4">
              @foreach($resultMetrics as $item_result)
                <li class="flex gap-2">
                  {{-- Checkmark Icon --}}
                  <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="24" height="24" rx="12" fill="url(#paint0_linear_2793_21186)"/>
                      <path d="M18.002 8.53955L9.34766 17.1938L4.75586 12.6021L5.81641 11.5415L9.34766 15.0728L16.9414 7.479L18.002 8.53955Z" fill="black"/>
                      <defs>
                      <linearGradient id="paint0_linear_2793_21186" x1="0" y1="24" x2="24" y2="0" gradientUnits="userSpaceOnUse">
                      <stop stop-color="#00FFA3"/>
                      <stop offset="0.48313" stop-color="#93FF82"/>
                      <stop offset="0.943979" stop-color="#EEFC51"/>
                      </linearGradient>
                      </defs>
                      </svg>
                  </div>
                  <div class="flex flex-col">
                    <x-heading
                      :as="HeadingTag::H4"
                      :color="TextColor::GRAY"
                    >
                      {{ $item_result['result_heading'] }}
                    </x-heading>
                    <x-text
                      :as="TextTag::SPAN"
                      :size="TextSize::MEDIUM"
                    >
                      {{ $item_result['result_description'] }}
                    </x-text>
                  </div>
                </li>
              @endforeach
            </ul>
        </div>
      </div>
    @endif
  </div>
  <div class="max-w-4xl mx-auto py-8">
    {{-- Bottom --}}
    @if($case_study_bottom_content_items)
      @foreach($case_study_bottom_content_items as $bottom_item)
        <div class="space-y-6">
          <x-heading
            :as="HeadingTag::H2"
            :size="HeadingSize::H3"
          >
            {{ $bottom_item['content_title'] }}
          </x-heading>
          @if($bottom_item['content_description'])
            <div>
              {!! apply_tailwind_classes_to_content($bottom_item['content_description']) !!}
            </div>
          @endif
        </div>
      @endforeach
    @endif 

    {{-- Testimonial Card --}}
    @if($testimonialReference)
      <div class="py-12">
        <x-testimonial-card-centered
          :post="$testimonialReference->ID"
          cardColor="green" 
        />
      </div> 
    @endif

    {{-- Success Points --}}
    <div class="space-y-6">
      <x-heading
        :as="HeadingTag::H2"
        :size="HeadingSize::H3"
      >
        {{ $successPointsHeading}}
      </x-heading>
      @if($successPoints)
        <ul class="space-y-4">
          @foreach($successPoints as $item_success)
            <li class="flex gap-2">
              {{-- Checkmark Icon --}}
              <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <rect width="40" height="40" rx="20" fill="url(#paint0_linear_552_13364)"/>
                  <mask id="mask0_552_13364" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="8" y="7" width="25" height="26">
                  <rect x="8" y="7.375" width="25" height="25" fill="#D9D9D9"/>
                  </mask>
                  <g mask="url(#mask0_552_13364)">
                  <path d="M17.2882 25.6042L20.5 23.1563L23.7119 25.6042L22.4445 21.5417L25.4133 19.5625H21.8195L20.5 15.4826L19.1806 19.5625H15.5868L18.5556 21.5417L17.2882 25.6042ZM14.0677 30.2917L16.4896 22.375L10.0834 17.7917H18L20.5 9.45834L23 17.7917H30.9167L24.5105 22.375L26.9323 30.2917L20.5 25.3958L14.0677 30.2917Z" fill="black"/>
                  </g>
                  <defs>
                  <linearGradient id="paint0_linear_552_13364" x1="0" y1="40" x2="40" y2="0" gradientUnits="userSpaceOnUse">
                  <stop stop-color="#00FFA3"/>
                  <stop offset="0.48313" stop-color="#93FF82"/>
                  <stop offset="0.943979" stop-color="#EEFC51"/>
                  </linearGradient>
                  </defs>
                </svg>
              </div>
              <div class="flex flex-col">
                <x-text
                  :as="TextTag::SPAN"
                  :size="TextSize::MEDIUM"
                >
                  {{ $item_success['success_point_text'] }}
                </x-text>
              </div>
            </li>
          @endforeach
        </ul>
      @endif
    </div>

  </div>
</article>