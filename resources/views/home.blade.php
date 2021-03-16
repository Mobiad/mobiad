@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="content">
                <div class="jumbotron py-4">
                    <h1>Karibu Yente!!</h1>
                    <p>
                        Yente ni huduma itakayokuwezesha kujipatia PESA, DAKIKA au BANDO la intaneti kama zawadi ya
                        kuweka matangazo ya biashara kama muito kwenye simu yako.

                        Kujiunga jaza fomu hapo chini.
                    </p>
                </div>
                @if (session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
                </div>
                @endif
                <div>
                    <form method="POST" action="{{ route('signup') }}">
                        @csrf
                        <div class="form-group">
                            <label for="fullName">Jina Kamili</label>
                            <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                placeholder="Mf: Miriam Makeba" id="fullName" name="fullname"
                                value="{{ old('fullname') }}" required>
                            @error('fullname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="phoneNumber">Namba ya Simu</label>
                            <br />
                            <input class="form-control @error('phone') is-invalid @enderror" type="tel"
                                placeholder="Mf: 0756123298" aria-describedby="phoneHelp" name="phone"
                                value="{{ old('phone') }}" required>
                            <small id="phoneHelp" class="form-text text-muted">
                                Hatutashiriki simu yako na mtu mwingine yeyote.
                            </small>
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="maleRadio" value="male" name="gender"
                                    checked value="{{ old('gender') }}" required>
                                <label class="form-check-label" for="maleRadio">Mwanaume</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="femaleRadio" value="female"
                                    name="gender" value="{{ old('gender') }}" required>
                                <label class="form-check-label" for="femaleRadio">Mwanamke</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="age">Umri</label>
                            <input type="number" class="form-control @error('age') is-invalid @enderror" id="age"
                                placeholder="Mf: 25" name="age" aria-describedby="age" value="{{ old('age') }}"
                                required>
                            @error('age')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="occupation">Kazi</label>
                            <input type="text" class="form-control @error('occupation') is-invalid @enderror"
                                placeholder="Fundi bomba, Biashara ya nguo, Kazi za nyumbani" id="occupation"
                                name="occupation" value="{{ old('occupation') }}" required>
                            @error('occupation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="region">Mkoa Unapoishi</label>
                                <input type="text" class="form-control @error('region') is-invalid @enderror"
                                    placeholder="Mf: Dar es Salaam" id="region" name="region"
                                    value="{{ old('region') }}" required>
                                @error('region')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="district">Wilaya Unayoishi</label>

                                <input type="text" class="form-control @error('district') is-invalid @enderror"
                                    placeholder="Mf: Kinondoni" id="district" name="district"
                                    value="{{ old('district') }}" required>
                                @error('district')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="street">Mtaa Unapoishi</label>

                                <input type="text" class="form-control @error('street') is-invalid @enderror"
                                    placeholder="Mf: Magomeni Mapipa" id="street" name="street"
                                    value="{{ old('street') }}" required>
                                @error('street')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-check-label">Ungependa upate zawadi gani ?</label>
                            @if($gifts)
                            @foreach ($gifts as $gift)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="{{$gift}}" id="{{$gift}}"
                                    name="gifts">
                                <label class="form-check-label" for="{{$gift}}">
                                    {{$gift}}
                                </label>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-check-label">Matangazo usiyopendelea kuwekewa</label>
                            @for($i = 0; $i < 3; $i++) <input type="text" class="form-control mb-1"
                                value="{{ old('opt_out_ads[$i]')}}" id="{{$i}}" name="opt_out_ads[]" placeholder="">
                                @endfor
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 pl-4">
                                <input type="checkbox"
                                    class="form-check-input @error('terms_and_conditions') is-invalid @enderror"
                                    id="terms_and_conditions" name="terms_and_conditions"
                                    {{ old('terms_and_conditions')? 'checked' : '' }} required>

                                <label class="form-check-label" for="terms_and_conditions">
                                    Nimekubali Vigezo na Masharti
                                </label>
                                @error('terms_and_conditions')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <small id="termsHelp" class="form-text text-muted d-none">
                                    Soma Vigezo na Masharti kabla ya kukubali na kuendelea
                                </small>
                            </div>
                            <div class="form-group col-md-6">
                                <a href="{{ route('terms') }}" target="blank">
                                    Bonyeza hapa kusoma vigezo na masharti
                                </a>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-50">{{ __('TUMA') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
