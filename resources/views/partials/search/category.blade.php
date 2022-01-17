<li id="category-{{$category->id}}" class="list-group-item @if(count($category->child_categories) > 0) dropend @endif">
    <a class="text-decoration-none"> {{$category->name}}</a>
    @if(count($category->child_categories) > 0)
    <a class=" dropdown-toggle dropdown-toggle-split" id="navbarDropdown-{{$category->id}}" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="visually-hidden">Toggle Dropdown</span>
    </a>
        <ul id="dropdown-{{$category->id}}" class="dropdown-menu" aria-labelledby="navbarDropdown-{{$category->id}}">
        @foreach ($category->child_categories as $subcategory)
          @include('partials.search.category', ['category' => $subcategory])
        @endforeach
        </ul>
    @endif
</li>

<script></script><script>
    $('#category-{{$category->id}}.dropend').on('click', function(e){
        e.stopPropagation();
        $('navbarDropdown-{{$category->id}}').dropdown('toggle');
    });
    
</script>