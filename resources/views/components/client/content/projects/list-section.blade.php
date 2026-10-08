@props(['categories' => collect(), 'projects' => collect()])
<!-- Projects List Section -->
<section class="py-16 lg:py-20 bg-[#F5EDE8]">
  <div class="w-[85%] max-w-[1320px] mx-auto">
    <x-client.content.projects.list-intro />
    <x-client.content.projects.list-filters :categories="$categories" />
    <x-client.content.projects.list-grid :projects="$projects" />
    <x-client.content.projects.list-pagination :projects="$projects" />
  </div>
</section>
