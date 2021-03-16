<form method="POST" action="{{ route('signup') }}" id="signup">
    @csrf
    <div class="form-group">
        <label for="fullName">Name / Jina</label>
        <input type="text" class="form-control @error('fullname') is-invalid @enderror"
            placeholder="Enter your name / Andika jina lako" id="fullName" name="fullname" value="{{ old('fullname') }}"
            required>
        @error('fullname')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>


    <div class="form-group">
        <label for="phoneNumber">Phone Number / Namba ya Simu</label>
        <br />
        <input class="form-control @error('phone') is-invalid @enderror" type="tel"
            placeholder="Enter your phone number/ Andika namba ya simu" aria-describedby="phoneHelp" name="phone"
            value="{{ old('phone') }}" required id="phoneNumber">
        <small id="phoneHelp" class="form-text text-muted">
            Hatutashiriki simu yako na mtu mwingine yeyote
        </small>
        @error('phone')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group">
        <label for="businessName">Business Name / Jina la Biashara</label>
        <input type="text" class="form-control @error('businessname') is-invalid @enderror"
            placeholder="Enter your business name / Andika jina la biashara yako" id="businessName" name="businessname"
            value="{{ old('businessname') }}" required>
        @error('businessname')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="businessDesc">Business Description (SCRIPT) / Maelezo ya Biashara</label>
        <textarea class="form-control @error('businessdesc') is-invalid @enderror"
            placeholder="Enter your business description here / Maelezo ya biashara yako" id="businessDesc"
            name="businessdesc" style="height: 100px" value="{{ old('businessdesc') }}" required></textarea>
        <small id="businessDescHelp" class="form-text text-muted">
            Gharama za kutengeneza muito wa biashara yako ni shilingi TZS 20,000/=
        </small>
        @error('businessdesc')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="subscriptionPeriod">Service Package (TZS 10,000 / Month / Phone Number)/ Miezi
            ya Huduma (TZS
            10,000 / Kwa Mwezi / Kwa Namba Moja) </label>
        <input type="number" class="form-control @error('subscription_period') is-invalid @enderror"
            id="subscriptionPeriod" placeholder="Eg: 3 months / Mf: Miezi 3" name="subscription_period"
            aria-describedby="subscription_period" value="{{ old('subscription_period') }}" required>
        <small id="businessDescHelp" class="form-text text-muted">
            Gharama za muito wa simu kwa namba moja ni shilingi TZS 10,000/= kwa Mwezi mmoja
            (TZS 10,000 / Kwa Mwezi / Kwa Namba Moja)
        </small>
        @error('subscription_period')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group">
        <label class="form-check-label">Phone numbers to activate the service / Namba za kuweka
            Muito</label>
        @for($i = 0; $i < 3; $i++) <input type="text" class="form-control mb-1"
            value="{{ old('subscriber_numbers[$i]')}}" id="{{$i}}" name="subscriber_numbers[]" placeholder="">
            @endfor
    </div>

    <div class="form-row">
        <div class="form-group col-md-6 pl-4">
            <input type="checkbox" class="form-check-input @error('terms_and_conditions') is-invalid @enderror"
                id="terms_and_conditions" name="terms_and_conditions" {{ old('terms_and_conditions')? 'checked' : '' }}
                required>

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
        <div class="form-group col-md-6 text-right">
            <a href="{{ route('terms') }}" target="blank">
                Bonyeza hapa kusoma vigezo na masharti
            </a>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary w-100">{{ __('SUBMIT / TUMA') }}</button>
    </div>
</form>
