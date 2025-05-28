@foreach($grades as $grade)
    <tr class="grade-header bg-gray-100">
        <td colspan="{{ array_reduce($quantifyItems->toArray(), function($carry, $items) { return $carry + count($items); }, 0) + 3 }}" 
            data-toggle="collapse" href="#grade-{{ $grade->id }}" 
            style="cursor: pointer">
            <strong>{{ $grade->name }}</strong>
            <i class="fas fa-chevron-down float-right"></i>
        </td>
    </tr>
    
    <tbody id="grade-{{ $grade->id }}" class="collapse show">
        @foreach($quantifyData[$grade->id] ?? [] as $banjiId => $row)
            <tr>
                <td>{{ $row['banji']->name }}</td>
                
                @foreach($quantifyItems as $type => $items)
                    @foreach($items as $item)
                        <td class="text-center">{{ $row['items'][$type][$item->id] ?? 0 }}</td>
                    @endforeach
                @endforeach
                
                <td class="text-center font-weight-bold">{{ $row['total'] }}</td>
                <td class="text-center">{{ $row['rank'] ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
@endforeach