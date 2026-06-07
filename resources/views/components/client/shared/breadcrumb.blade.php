@props([
    'items' => null,
    'currentLabel' => null,
    'parentLabel' => null,
    'parentHref' => null,
    'wrapperClass' => '',
    'listClass' => 'font-bold text-primary/60 uppercase text-xs md:text-base',
    'textClass' => null,
    'linkClass' => 'hover:text-secondary transition-colors',
    'currentClass' => 'text-primary font-semibold border-primary',
    'separatorClass' => 'mx-1',
    'separatorStyle' => 'slash',
])

@php
    $breadcrumbItems = collect($items);

    if ($breadcrumbItems->isEmpty() && filled($currentLabel)) {
        $breadcrumbItems = collect([
            ['label' => 'Trang chá»§', 'url' => route('client.home')],
        ]);

        if (filled($parentLabel)) {
            $breadcrumbItems->push(['label' => $parentLabel, 'url' => $parentHref]);
        }

        $breadcrumbItems->push(['label' => $currentLabel]);
    }

    $breadcrumbItems = $breadcrumbItems
        ->filter(fn ($item) => filled(data_get($item, 'label')))
        ->values();

    $resolvedListClass = $textClass ?? $listClass;
@endphp

@if ($breadcrumbItems->isNotEmpty())
    <div {{ $attributes->merge(['class' => $wrapperClass]) }}>
        <nav class="{{ $resolvedListClass }}" aria-label="Breadcrumb">
            @foreach ($breadcrumbItems as $item)
                @php
                    $isLast = $loop->last;
                    $label = data_get($item, 'label');
                    $url = data_get($item, 'url');
                @endphp

                @if (! $isLast && filled($url))
                    <a href="{{ $url }}" class="{{ $linkClass }}">{{ $label }}</a>
                @else
                    <span class="{{ $isLast ? $currentClass : '' }}">{{ $label }}</span>
                @endif

                @unless ($isLast)
                    @if ($separatorStyle === 'chevron')
                        <svg class="{{ $separatorClass }}" width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M1 1L5 5L1 9" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    @else
                        <span class="{{ $separatorClass }}" aria-hidden="true">/</span>
                    @endif
                @endunless
            @endforeach
        </nav>
    </div>
@endif
