@section("sidebar")
	<div class="sidebar">
    <ul class="nav nav-sidebar">
      <li><a href="{!! URL::to('admin/dashboard') !!}">
      	<i class="fa fa-dashboard"></i>
      	<span>Dashboard</span>
      </a></li>

			
    <div class="text-right collapse-button">
      <button id="sidebar-collapse" class="btn btn-default" style="">
	      <i class="fa fa-angle-right"></i>
      </button>
    </div>
  </div>
@show
