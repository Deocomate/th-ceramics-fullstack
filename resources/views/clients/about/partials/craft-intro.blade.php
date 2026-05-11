@php
  $craftHeadline = $about->nt_head ?? 'Từ vật liệu xây dựng đến di sản kiến trúc';
  $craftHeadlineLines = preg_split('/\r\n|\r|\n/', trim((string) $craftHeadline)) ?: [];
  $craftBody = $about->nt_body ?? 'Chúng tôi dành nhiều năm nghiên cứu để phục chế mẫu ngói âm dương.';
@endphp

<!-- Craft Section 1: Intro Text / Từ vật liệu xây dựng đến di sản kiến trúc -->
<div
  class="max-w-2xl mx-auto text-center my-[35px] md:mb-24 md:mt-24"
  data-aos="fade-up"
>
  <h2
    class="text-[20px] md:text-4xl font-bold text-textPrimary mb-6 md:mb-8 flex flex-col gap-2 md:gap-4 items-center leading-[32px] md:leading-normal"
  >
    @foreach ($craftHeadlineLines as $line)
      <span>{{ $line }}</span>
    @endforeach
  </h2>
  <p
    class="text-textPrimary leading-[28px] text-justify md:text-center font-medium tracking-[0.48px] md:tracking-wide"
  >
    {{ $craftBody }}
  </p>
</div>
