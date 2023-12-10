<tr>
    <td>{{ $category->id }}</td>
    <td>
        @for($i = 0; $i < $level; $i++)
            &nbsp;&nbsp;&nbsp;
        @endfor

       <i class="{{ $category->icon }}"></i>
        {{ $category->name }}
    </td>
    <td>

        <a href="{{ route('category.add', $category->id) }}" class="btn btn-sm btn-warning">Edit</a>
        <button type="button" data-id="{{ $category->id }}" onclick="deleteCategory(this)" class="btn btn-sm btn-danger">Delete</button>

    </td>
</tr>
@foreach($category->subcategories as $subcategory)
    @include('AdminViews.Categories.subcategory_row', ['category' => $subcategory, 'level' => $level + 1])
@endforeach
