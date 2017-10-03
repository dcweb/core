@extends("dcms::template/layout")

@section("content")

    <div class="main-header">
      <h1>Countries</h1>
      <ol class="breadcrumb">
        <li><a href="{!! URL::to('admin/dashboard') !!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{!! URL::to('admin/settings') !!}"> settings</a></li>
        <li class="active"><i class="fa fa-pencil"></i> Countries</li>
      </ol>
    </div>

    <div class="main-content">
    	<div class="row">
				<div class="col-md-12">
					<div class="main-content-block">

  @if (Session::has('message'))
    <div class="alert alert-info">{!! Session::get('message') !!}</div>
  @endif

    <div class="btnbar btnbar-right"><a class="btn btn-small btn-primary" href="{!! URL::to('/admin/settings/countries/create') !!}">Create new</a></div>

    <h2>Overview</h2>

    <table id="datatable" class="table table-hover table-condensed" style="width:100%">
        <thead>
            <tr>
                <th>Country</th>
                <th>Flag</th>
                <th></th>
            </tr>
        </thead>
    </table>

<script type="text/javascript">
$(document).ready(function() {
    oTable = $('#datatable').DataTable({
        "pageLength": 50,
        "processing": true,
        "serverSide": false,
        "ajax": "{{ route('admin.settings.countries.api.table') }}",
        "columns": [
            {data: 'country', name: 'Country'},
            {data: 'country_name', name: 'country_name'},
            {data: 'edit', name: 'edit', orderable: false, searchable: false}
        ]
    });
});
</script>

    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/be7019ee387/integration/bootstrap/3/dataTables.bootstrap.css">

    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/1.10.0/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="//cdn.datatables.net/plug-ins/be7019ee387/integration/bootstrap/3/dataTables.bootstrap.js"></script>

	      	</div>
      	</div>
      </div>
    </div>

@stop
