@extends('_layouts.master')

@push('meta')
<meta property="og:title" content="{{ $page->title }}" />
<meta property="og:type" content="article" />
<meta property="og:url" content="{{ $page->getUrl() }}" />
<meta property="og:description" content="{{ $page->description }}" />
@endpush

@section('body')
@if ($page->cover_image)
<img src="{{ $page->cover_image }}" alt="{{ $page->title }} cover image" class="mb-2">
@endif

<h1 class="leading-none mb-2 post-title md:text-6xl text-center text-white">{{ $page->title }}</h1>

<p class="text-gray-600 text-xl md:mt-4 text-center">{{ date('F j, Y', $page->date) }}</p>

<div class="my-16"></div>

@if ($page->categories)
@foreach ($page->categories as $i => $category)
<a href="{{ '/blog/categories/' . $category }}" title="View posts in {{ $category }}"
    class="inline-block bg-gray-300 hover:bg-dark-400 leading-loose tracking-wide text-gray-800 uppercase text-xs font-semibold rounded mr-4 px-3 pt-px">{{ $category }}</a>
@endforeach
@endif

<div class="border-b border-blue-200 mb-10 pb-4" v-pre>
    @yield('content')
</div>

<nav class="flex justify-between text-sm md:text-base">
    <div>
        @if ($next = $page->getNext())
        <a href="{{ $next->getUrl() }}" title="Older Post: {{ $next->title }}">
            &LeftArrow; {{ $next->title }}
        </a>
        @endif
    </div>

    <div>
        @if ($previous = $page->getPrevious())
        <a href="{{ $previous->getUrl() }}" title="Newer Post: {{ $previous->title }}">
            {{ $previous->title }} &RightArrow;
        </a>
        @endif
    </div>
</nav>

@endsection
@push('scripts')
<script>
function setFlickerAnimation() {
  // get all elements that should be animated
  const animatedElements = Array.from(
    document.querySelectorAll(".post-title")
  );

  if (!animatedElements.length) {
    return false;
  }

  // helper function to wrap random letters in <span>
  const wrapRandomChars = (str, iterations = 1) => {
    const chars = str.split("");
    const excludedChars = [" ", "-", ",", ";", ":", "(", ")"];
    const excludedIndexes = [];
    let i = 0;

    // run for the number of letters we want to wrap
    while (i < iterations) {
      const randIndex = Math.floor(Math.random() * chars.length);
      const c = chars[randIndex];

      // make sure we don't wrap a space or punctuation char
      // or hit the same letter twice
      if (!excludedIndexes.includes(randIndex) && !excludedChars.includes(c)) {
        chars[randIndex] = `<span class="flicker">${c}</span>`;
        excludedIndexes.push(randIndex);
        i++;
      }
    }

    return chars.join("");
  };

  // replace the plain text content in each element
  animatedElements.forEach((el) => {
    const text = el.textContent.trim();
    const count = el.dataset.flickerChars ? parseInt(el.dataset.flickerChars) : undefined
    el.innerHTML = wrapRandomChars(text, count);
  });
}

setFlickerAnimation();
</script>
@endpush