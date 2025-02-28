@extends('layouts.custom-master')

@section('styles')
<style>
    .input100 option:first-child {
        color: #999999;
    }
    .input100 {
        color: #999999;
    }
    .input100:focus {
        color: #666666;
    }
</style>
@endsection

@section('content')

            <div class="">
				<!-- CONTAINER OPEN -->
				<div class="col col-login mx-auto mt-7">
					<div class="text-center">
						<a href="{{url('index')}}">
							<img src="{{asset('build/assets/images/brand/logo.png')}}" class="header-brand-img" alt="logo">
						</a>
					</div>
				</div>
				<div class="container-login100">
					<div class="wrap-login100 p-6">
						<form class="login100-form validate-form" method="POST" action="{{ route('register.store') }}">
                            @csrf
							<span class="login100-form-title">
								Registration
							</span>
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
							<div class="wrap-input100 validate-input" data-validate = "Username is required">
								<input class="input100" type="text" name="name" value="{{ old('name') }}" placeholder="User name" required>
								<span class="focus-input100"></span>
								<span class="symbol-input100">
									<i class="zmdi zmdi-account" aria-hidden="true"></i>
								</span>
							</div>
							<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
								<input class="input100" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
								<span class="focus-input100"></span>
								<span class="symbol-input100">
									<i class="zmdi zmdi-email" aria-hidden="true"></i>
								</span>
							</div>
                            <div class="wrap-input100 validate-input" data-validate = "Department is required">
								<select class="input100" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
								<span class="focus-input100"></span>
								<span class="symbol-input100">
									<i class="zmdi zmdi-city" aria-hidden="true"></i>
								</span>
							</div>
							<div class="wrap-input100 validate-input" data-validate = "Password is required">
								<input class="input100" type="password" name="password" placeholder="Password" required>
								<span class="focus-input100"></span>
								<span class="symbol-input100">
									<i class="zmdi zmdi-lock" aria-hidden="true"></i>
								</span>
							</div>
							<label class="custom-control custom-checkbox mt-4">
								<input type="checkbox" class="custom-control-input" name="terms" required>
								<span class="custom-control-label">Agree the <a href="{{url('terms')}}">terms and policy</a></span>
							</label>
							<div class="container-login100-form-btn">
								<button type="submit" class="login100-form-btn btn-primary">
									Register
								</button>
							</div>
							<div class="text-center pt-3">
								<p class="text-dark mb-0">Already have account?<a href="{{url('login')}}" class="text-primary ms-1">Sign In</a></p>
							</div>
						</form>
					</div>
				</div>
				<!-- CONTAINER CLOSED -->
			</div>

@endsection

@section('scripts')

@endsection
