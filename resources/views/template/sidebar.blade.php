@section("sidebar")
	<div class="sidebar">
    <ul class="nav nav-sidebar">


		@foreach(Config::get('dcms_sidebar') as $group => $groupsettings )

			@if(count($groupsettings['links'])>1)
			<li class="dropdown">
        		<a href="{!! URL::to($groupsettings['links'][0]['route']) !!}"><i class="fa {{$groupsettings['icon']}}"></i><span>{{$group}}</span><b class="arrow fa fa-angle-down"></b></a>
				<ul class="dropdown-menu">
					@foreach($groupsettings['links'] as $linkdetails)
					<li><a href="{!! URL::to($linkdetails['route']) !!}">{{$linkdetails['label']}}</a></li>
					@endforeach
				</ul>
			</li>
			@else
			<li>
				<a href="{!! URL::to($groupsettings['links'][0]['route']) !!}">
	      			<i class="fa {{$groupsettings['icon']}}"></i><span>{{$group}}</span>
		      	</a>
			</li>
			@endif
		@endforeach


	</ul>

    <div class="text-right collapse-button">
      <button id="sidebar-collapse" class="btn btn-default" style="">
	      <i class="fa fa-angle-right"></i>
      </button>
    </div>
  </div>
@show
