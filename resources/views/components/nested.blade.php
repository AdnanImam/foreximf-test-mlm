@foreach ($datas as $data)
    <li>
        @if ($data->children)
            <span class="caret">{{$data->name}} (level {{$data->getLevel()}})</span>
            <ul class="nested">
                @component('components.nested',[
                    'datas' => $data->children
                ])
                @endcomponent
            </ul>
        @else
            {{$data->name}}
        @endif
    </li>
@endforeach