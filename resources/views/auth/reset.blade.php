@extends('app', ['page_title' => 'Reset Password', 'page_class' => 'reset'])

@section('body')
<div class="content">
  <div class="form-container py-100-xl ma-auto">
    <section class="head">
			<h1 class="title text-center">Enter your new password</h1>
		</section>A

		<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="token" value="{{ $token }}">

			<div class="form-group">
				<label for="email">Enter Your Email</label>
                <span class="error-message">{{array_key_exists("email", $errors->messages()) ? $errors->messages()["email"][0] : ''}}</span>
				<input type="email" class="text-input {{(array_key_exists("email", $errors->messages()) ? ' error' : '')}}"
                       name="email" value="{{ old('email') }}" placeholder="Enter your email here">
			</div>

			<div class="form-group mt-xl">
				<label for="password">Enter New Password</label>
                <span class="error-message">{{array_key_exists("password", $errors->messages()) ? $errors->messages()["password"][0] : ''}}</span>
				<input type="password" class="text-input {{(array_key_exists("password", $errors->messages()) ? ' error' : '')}}"
                       name="password" placeholder="Enter new password here">
			</div>

			<div class="form-group mt-xl">
				<label for="password_confirmation">Confirm New Password</label>
                <span class="error-message">{{array_key_exists("password_confirmation", $errors->messages()) ? $errors->messages()["password_confirmation"][0] : ''}}</span>
				<input type="password" class="text-input {{(array_key_exists("password_confirmation", $errors->messages()) ? ' error' : '')}}"
                       name="password_confirmation" placeholder="Enter new password here">
			</div>

			<div class="form-group mt-xxxl">
				<button type="submit" class="btn btn-primary">Set New Password</button>
			</div>
		</form>
	</div>
</div>
@endsection
