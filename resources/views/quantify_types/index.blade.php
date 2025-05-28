@extends('layouts.app')

@section('content')
<div class="container">
  <div class="col-md-10 offset-md-1">
    <div class="card ">
      <div class="card-header">
        <h1>
          QuantifyType
          <a class="btn btn-success float-xs-right" href="{{ route('quantify_types.create') }}">Create</a>
        </h1>
      </div>

      <div class="card-body">
        @if($quantify_types->count())
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th class="text-xs-center">#</th>
                <th>Parent_id</th> <th>Code</th> <th>Name</th> <th>Weight</th> <th>Order</th> <th>Requirements</th> <th>User_id</th>
                <th class="text-xs-right">OPTIONS</th>
              </tr>
            </thead>

            <tbody>
              @foreach($quantify_types as $quantify_type)
              <tr>
                <td class="text-xs-center"><strong>{{$quantify_type->id}}</strong></td>

                <td>{{$quantify_type->parent_id}}</td> <td>{{$quantify_type->code}}</td> <td>{{$quantify_type->name}}</td> <td>{{$quantify_type->weight}}</td> <td>{{$quantify_type->order}}</td> <td>{{$quantify_type->requirements}}</td> <td>{{$quantify_type->user_id}}</td>

                <td class="text-xs-right">
                  <a class="btn btn-sm btn-primary" href="{{ route('quantify_types.show', $quantify_type->id) }}">
                    V
                  </a>

                  <a class="btn btn-sm btn-warning" href="{{ route('quantify_types.edit', $quantify_type->id) }}">
                    E
                  </a>

                  <form action="{{ route('quantify_types.destroy', $quantify_type->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete? Are you sure?');">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="DELETE">

                    <button type="submit" class="btn btn-sm btn-danger">D </button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {!! $quantify_types->render() !!}
        @else
          <h3 class="text-xs-center alert alert-info">Empty!</h3>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
