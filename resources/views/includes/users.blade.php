<tr>
    
    <td class="odd-grid-col text-center">{{ $index }}</th>
    <td class="odd-grid-col">{{ $user->name }}</th>
    <td class="odd-grid-col">{{ $user->email }}</th>
    <td class="odd-grid-col">{{ $user->phone }}</th>
    <td class="odd-grid-col">{{ $user->position }}</th>
<td class="odd-grid-col text-center"><img src="{{ $user->photo }}" width="70" height="70" ></th>
</tr>