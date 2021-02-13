@extends('nav.headerNav')

@section('content')
<div class="content">
    <div class="container-fluid" style="font-size: 12px">
        <table class="table table-sm table-striped">
            <thead>
            <tr>
                <th scope="col" hidden>Log ID</th>
                <th scope="col">Action</th>
                <th scope="col">Date</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                <tr>
                    <th scope="row" hidden>{{$log->id}}</th>
                    <td class="p-0">{{$log->action}}</td>
                    <td class="p-0">{{$log->created_at}}</td>
                  </tr>
                @endforeach
               
            </tbody>
        </table>
        {{ $logs->links() }}
    </div>
</div>




@endsection