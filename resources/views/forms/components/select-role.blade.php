@php
use Spatie\Permission\Models\Role;
$roles= Role::all();
@endphp
<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @foreach($roles as $role)
    <div x-data="{ state: $wire.$entangle({{ $getStatePath() }}) }" x-init="state=fillState()">
        <!-- Interact with the `state` property in Alpine.js -->
        <label for="{{$role->id}}">{{$role->name}}</label>
        <input class='checkbox-role' type="checkbox" id="{{$role->id}}" name="{{$role->name}}" value="{{$role->id}}" 
        @if(!is_null($getRecord()) && $getRecord()->hasRole($role->name))
        checked
        @endif
        >
    </div>
    @endforeach
</x-dynamic-component>

<script>
    function fillState() {
        const checkboxes = document.getElementsByClassName('checkbox-role');
        let roles = [];
        for (let i = 0; i < checkboxes.length; i++) {
            if (!checkboxes[i].checked) {
                continue;
            }
            role.push(checkboxes[i].id);
        }
        return roles;
    }
</script>