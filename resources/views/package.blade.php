@extends('layout.base')

@section('body')
<div id="header" class="content-block header-wrapper">
	<div class="header-wrapper-inner">
		<section class="top clearfix">
			<div class="pull-left">
				<h1><a class="logo" href="/">Lanjalan</a></h1>
			</div>
			<div class="pull-right">
				<a class="toggleDrawer" href="#"><i class="fa fa-bars fa-2x"></i></a>
			</div>
		</section>
		<section class="package-detail">
			<div class="container">
				<div class="row">
					<div class="name">
						{{ $package->name }}
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col col-sm-4 image">
						<img src="{{ $randomimage[0] }}" alt="{{ $package->name }}"/>
					</div>
					<div class="col col-sm-8 description">
						<h3>Deskripsi</h3>
						<p>{{ $package->description }}</p>
						<h3>Biaya</h3>
						<p>{{ $package->price }}</p>
						<a href="{{ $package->link }}" class="btn btn-o-white btn-lg">BELI</a>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<div id="recommendation" class="content-block">
	<div class="container">
		<header class="block-heading cleafix">
			<h1>Recommendation</h1>
		</header>
		<section class="block-body">
			<div class="row">
				@if($error)
				<div class="col-sm-12">
					<p>
						Recommendation Error:
						<br/>
						{{ $error }}
					</p>
				</div>
				@else
				@foreach($recommendation as $package)
				<div class="col-sm-3 travel-package">
					<a href="/package/{{ $package->id }}" class="recent-work imagelink" style="background-image:url({{ $randomimage[0] }})">
						<span class="btn btn-o-white imagename">{{ $package->name }}</span>
					</a>
					<span class="price">Starts from Rp. {{ explode(' - ', $package->price)[0] }}</span>
				</div>
				@endforeach
				@endif
			</div>
		</section>
	</div>
</div>
@endsection