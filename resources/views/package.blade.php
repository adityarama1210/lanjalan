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
						{{ $data->name }}
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col col-sm-4 image">
						<img src="{{ $randomimage[0] }}" alt="{{ $data->name }}"/>
					</div>
					<div class="col col-sm-8 description">
						<h3>Deskripsi</h3>
						<p>{{ $data->description }}</p>
						<h3>Biaya</h3>
						<p>{{ $data->price }}</p>
						<a href="{{ $data->link }}" class="btn btn-o-white btn-lg">BELI</a>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>
@endsection