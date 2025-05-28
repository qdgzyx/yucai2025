@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>
          QuantifyItem
          <a class="btn btn-success float-xs-right" href="{{ route('quantify_items.create') }}">Create</a>
        </h1>
      </div>

      <div class="card-body">
        @if($quantify_items->count())
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th class="text-xs-center">#</th>
                <th>Semester_id</th> <th>Name</th> <th>Type</th> <th>Score</th> <th>Criteria</th> <th>Status</th>
                <th class="text-xs-right">OPTIONS</th>
              </tr>
            </thead>

            <tbody>
              @foreach($quantify_items as $quantify_item)
              <tr>
                <td class="text-xs-center"><strong>{{$quantify_item->id}}</strong></td>

                <td>{{$quantify_item->semester_id}}</td> <td>{{$quantify_item->name}}</td> <td>{{$quantify_item->type}}</td> <td>{{$quantify_item->score}}</td> <td>{{$quantify_item->criteria}}</td> <td>{{$quantify_item->status}}</td>

                <td class="text-xs-right">
                  <a class="btn btn-sm btn-primary" href="{{ route('quantify_items.show', $quantify_item->id) }}">
                    V
                  </a>

                  <a class="btn btn-sm btn-warning" href="{{ route('quantify_items.edit', $quantify_item->id) }}">
                    E
                  </a>

                  <form action="{{ route('quantify_items.destroy', $quantify_item->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="DELETE">

                    <button type="submit" class="btn btn-sm btn-danger">D </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {!! $quantify_items->render() !!}
        @else
          <h3 class="text-xs-center alert alert-info">Empty!</h3>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
