@extends('layouts.app')

@section('content')
<div class="container d-flex main justify-content-center">
    <div class="row rc align-self-center text-white">
        <div class="col-sm-5 br d-flex justify-content-center">
            <h1 class="align-self-center"> {{ config('app.name') }} </h1>
        </div>

        <div class="col-sm-7">
            <div class="card">
                <div class="card-body p-0">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                </div>
                                
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="{{ __('E-Mail Address') }}" autocomplete="email" required autofocus>
                            </div>

                            @error('email')
                            <?php
                                echo "<script>
                                        swal({
                                            title: 'Error!',
                                            text: '".$message."',
                                            icon: 'error',
                                            button: 'Let me fix!',
                                        });
                                    </script>";
                            ?>
                            @enderror
                        </div>

                        <div class="form-group">
                            {{-- <label for="password" class="col-md-4 col-form-label text-md-right"></label> --}}

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                                </div>
                                
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" required>
                            </div>

                            @error('password')
                            <?php
                                echo "<script>
                                        swal({
                                            title: 'Error!',
                                            text: '".$message."',
                                            icon: 'error',
                                            button: 'Let me fix!',
                                        });
                                    </script>";
                            ?>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="form-check p-0">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" name="remember" id="customCheck1" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="customCheck1"> {{ __('Remember Me') }} </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-dark">
                                {{ __('Login') }}
                            </button>

                            {{-- @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script>
</script>
@endsection