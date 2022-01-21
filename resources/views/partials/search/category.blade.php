<li id="category-li-{{$category->id}}" class="list-group-item cat-item @if(count($category->child_categories) > 0) dropend @endif">
    <input name="{{$category->id}}" id="category-{{$category->id}}" class="form-check-input" type="checkbox"
    value="category-active">
    <a class="text-decoration-none text-black"> {{$category->name}}</a>
    @if(count($category->child_categories) > 0)
    <a href="#" class="dropdown-toggle dropdown-toggle-split icon-click category-dropdown-{{$level}}" id="navbarDropdown-{{$category->id}}" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="visually-hidden">Toggle Dropdown</span>
    </a>
        <ul id="dropdown-{{$category->id}}" class="dropdown-menu p-2" aria-labelledby="navbarDropdown-{{$category->id}}">
        @foreach ($category->child_categories as $subcategory)
          @include('partials.search.category', ['category' => $subcategory, 'level' => $level + 1])
        @endforeach
        </ul>
    @endif
</li>
 
<script>
    let catIsOpen{{$category->id}} = false;
    $('#category-li-{{$category->id}}').on('click', function(e) {
        e.stopPropagation();
    });
    
    $('#category-li-{{$category->id}}.dropend').on('click', function(e){
        e.stopPropagation();
        $('.category-dropdown-{{$level}}').dropdown('hide');
        if(!catIsOpen{{$category->id}}){
            $('#navbarDropdown-{{$category->id}}').dropdown('toggle');
            catIsOpen{{$category->id}} = true;
        } else catIsOpen{{$category->id}} = false;
    });
    
</script>