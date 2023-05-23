@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <input type="date" id="start" name="start">
                    <input type="date" id="end" name="end">
                    <button id="filter">filter</button>
                    <table class="table table-bordered datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Location</th>
                            <th>Temperature</th>
                            <th>DateTime</th>
                            <th width="100px">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    var table = $('.datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('home') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'location', name: 'location'},
            {data: 'temperature', name: 'temperature'},
            {data: 'date_time', name: 'date_time'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('.datatable tbody').on( 'click', 'a', function () {
        var data = table.row( $(this).parents('tr') ).data();
        data.temperature = (data.temperature * (9/5)) + 32;
        $(this).parents('tr').find('td:eq(2)').html(data.temperature);
        $(this).remove()
        } );

    $('#filter').on('click', function(){
        var start = $('#start').val();
        var end = $('#end').val();
        $.ajax({
           type:'GET',
           url:"{{route('home')}}?start="+start+"&end="+end,
           data:'_token = <?php echo csrf_token() ?>',
           success:function(data) {
                table.distroy();
                table.reload();
               }
            });
        })
  });

</script>
@endsection
