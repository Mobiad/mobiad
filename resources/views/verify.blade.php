@extends('layouts.one')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Phone Number') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    @if (session('warning'))
                    <div class="alert alert-warning">
                        {{ session('warning') }}
                    </div>
                    @endif
                    @if (session('customer'))
                    <div class="alert alert-success" role="alert">
                        {{ __('A fresh verification otp  message has been sent to your phone number.') }}
                        {{ __('Before proceeding, please check your phone for a verification otp message.') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('verify') }}">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" value="{{ $phone }}" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="otpCode">Verification code</label>
                            <input type="text" class="form-control @error('otp') is-invalid @enderror" id="otpCode"
                                name="otp" value="{{ old('otp') }}" required>
                            @error('otp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Verify  now') }}</button>

                    </form>


                    {{ __('If you did not receive the otp message') }},
                    <form class="d-inline" method="POST" action="{{ route('verify.resend') }}">

                        @csrf
                        <input type="hidden" value="{{ $phone }}" name="phone">
                        <button type="submit"
                            class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
