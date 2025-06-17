{{-- 修改表格显示逻辑，增加排名样式 --}}
<thead class="thead-light">
    <tr>
        <th>小组</th>
        @foreach($quantifyItems as $type => $items)
            @foreach($items as $item)
                <th title="{{ $item->criteria }}">{{ $item->name }} （{{ $item->score }}）</th>
            @endforeach
        @endforeach
        <th>合计</th>
        <th>排名</th>
    </tr>
</thead>

<tbody>
    @foreach($groupQuantifyData as $groupId => $row)
        <tr class="{{ $row['rank'] === 1 ? 'table-success' : '' }}">
            <td>{{ $row['group']->name }} ({{ $row['group']->banji->name ?? '' }})</td>
            
            @foreach($quantifyItems as $type => $items)
                @foreach($items as $item)
                    <td class="text-center">{{ $row['items'][$type][$item->id] ?? 0 }}</td>
                @endforeach
            @endforeach
            
            <td class="text-center font-weight-bold">{{ $row['total'] }}</td>
            <td class="text-center">
                {{-- 新增排名徽章样式 --}}
                @if($row['rank'])
                    <span class="badge badge-{{ $row['rank'] <= 3 ? 'primary' : 'secondary' }}">
                        {{ $row['rank'] }}
                    </span>
                @else
                    -
                @endif
            </td>
        </tr>
    @endforeach
</tbody>