@extends('layout.base')

@section('body')
<div id="home">
	<div id="header" class="content-block header-wrapper-image">
		<div class="header-wrapper-inner">
			<section class="top clearfix">
				<div class="pull-left">
					<h1><a class="logo" href="/">Lanjalan</a></h1>
				</div>
				<div class="pull-right">
					<a class="toggleDrawer" href="#"><i class="fa fa-bars fa-2x"></i></a>
				</div>
			</section>
			<section class="center">
				<div class="slogan">
					UNFORGETTABLE TRAVELLING EXPERIENCES AWAIT
				</div>
				<div class="secondary-slogan">
					Find the right travel package for YOU!
				</div>
				<div class="searchbar">
					<form action="{{ route('search') }}" method="GET">
						@if(session('error'))
						<h4>{{ session('error') }}</h4>
						@endif
						<div class="form-group">
							<input type="text" name="query" id="query" class="form-control form-control-white" placeholder="Search" required>
						</div>
						<input type="hidden" name="min" id="min" value=-1>
						<input type="hidden" name="max" id="max" value=-1>

						<div id="price-slider" class="form-group">
							<!--<span>Price Range</span>-->
							<label>Price Range</label>
							<div id="slider-range"></div>
							<p id="amount"></p>
						</div>
						<input type="submit" class="btn btn-o-white" value="Search">
					</form>
				</div>
			</section>
			<section class="bottom">
				<a id="scrollToContent" href="#">
					<img src="{{ asset('images/arrow_down.png') }}">
				</a>
			</section>
		</div>
	</div>
</div>


@if($search)
<div class="content-block" id="search-result">
	<div class="container">
		<header class="block-heading cleafix">
			<h1>Search Result</h1>
		</header>
		<section class="block-body">
			<div class="row">
				@if($error)
				<div class="col-sm-12">
					<p>Travel package not found. Please try another query.</p>
				</div>
				@else
				@foreach($data as $package)
				<div class="col-sm-4 travel-package">
					<?php $randomint = rand(1, 8) ?>
					<a href="/package/{{ $package->id }}" class="recent-work imagelink" style="background-image:url({{ asset('images/randomimage/img'.$randomint.'.jpg') }})">
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
@else
<div class="content-block parallax" id="services">
	<div class="container text-center">
		<header class="block-heading cleafix">
			<h1>Our Services</h1>
			<p>A little about what we do</p>
		</header>
		<section class="block-body">
			<div class="row">
				<div class="col-md-4">
					<div class="service">
						<i class="fa fa-send-o"></i>
						<h2>Illustration</h2>
						<p>In at accumsan risus. Nam id volutpat ante. Etiam vel mi mattis, vulputate nunc nec, sodales nibh. Etiam nulla magna, gravida eget ultricies sit amet, hendrerit in lorem.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="service">
						<i class="fa fa-heart-o"></i>
						<h2>3D Modeling</h2>
						<p>In at accumsan risus. Nam id volutpat ante. Etiam vel mi mattis, vulputate nunc nec, sodales nibh. Etiam nulla magna, gravida eget ultricies sit amet, hendrerit in lorem.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="service">
						<i class="fa fa-camera-retro"></i>
						<h2>Photography</h2>
						<p>In at accumsan risus. Nam id volutpat ante. Etiam vel mi mattis, vulputate nunc nec, sodales nibh. Etiam nulla magna, gravida eget ultricies sit amet, hendrerit in lorem.</p>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>

<div class="content-block" id="testimonials">
	<div class="container">
		<header class="block-heading cleafix">
			<h1>Testimonials</h1>
			<p>Some happy customers have to say.</p>
		</header>
		<section class="block-body">
			<div class="row">
				<div class="col-md-4">
					<div class="testimonial">
						<img src="{{ asset('images/testimonial_31-190x190.jpg') }}">
						<p>In at accumsan risus. Nam id volutpat ante. Etiam vel mi mattis, vulputate nunc nec, sodales nibh. Etiam nulla magna, gravida eget ultricies sit amet, hendrerit in lorem.</p>
						<strong>Jhon Doe</strong><br/>
						<span>Head of Ideas, Technext</span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="testimonial">
						<img src="{{ asset('images/testimonial_11-190x190.jpg') }}">
						<p>In at accumsan risus. Nam id volutpat ante. Etiam vel mi mattis, vulputate nunc nec, sodales nibh. Etiam nulla magna, gravida eget ultricies sit amet, hendrerit in lorem.</p>
						<strong>Jane Doe</strong><br/>
						<span>CEO, Apple Inc</span>
					</div>
				</div>
				<div class="col-md-4">
					<div class="testimonial">
						<img src="{{ asset('images/testimonial_22-190x190.jpg') }}">
						<p>In at accumsan risus. Nam id volutpat ante. Etiam vel mi mattis, vulputate nunc nec, sodales nibh. Etiam nulla magna, gravida eget ultricies sit amet, hendrerit in lorem.</p>
						<strong>Albert Doe</strong><br/>
						<span>Team Lead, Design Studio</span>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>
@endif

<div class="content-block" id="contact">
	<div class="container text-center">
		<header class="block-heading cleafix">
			<h1>Contact Us</h1>
			<p>Feel free to drop us a line.</p>
		</header>
		<section class="block-body">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<form class="" role="form">
						<div class="form-group">
							<input type="email" class="form-control form-control-white" id="subject" placeholder="Your Name" required>
						</div>
						<div class="form-group">
							<input type="email" class="form-control form-control-white" id="exampleInputEmail2" placeholder="Enter email" required>
						</div>
						<div class="form-group">
							<textarea class="form-control form-control-white" placeholder="Write Something" required></textarea>
						</div>
						<input type="submit" class="btn btn-o-white" value="Say Hello">
					</form>
				</div>
			</div>
		</section>
	</div>
	@endsection