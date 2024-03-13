<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        <ul>
            @foreach($getState() as $category)
            <li>{{ $category['name'] }}</li>
            @endforeach
        </ul>
    </div>
</x-dynamic-component>