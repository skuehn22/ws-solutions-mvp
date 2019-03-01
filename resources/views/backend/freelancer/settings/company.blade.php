<form data-toggle="validator" role="form" id="company-data" name="company-data" method="POST" action="/freelancer/settings/save-company">
<div class="row">

    <div class="col-md-6">
        <h4>Your Company Details</h4>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-10">
                @if(Session::has('error'))
                    <div class="alert alert-danger error_message">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        {{ Session::get('error') }}
                    </div>
                @endif
                @if(Session::has('success'))
                    <div class="alert alert-success success_message">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        {{ Session::get('success') }}
                    </div>
                @endif
            </div>
            <div class="col-md-12">

                <div class="form-row py-2">
                    <label class="col-md-3 col-form-label" for="company">
                        Company
                        <i class="fas fa-info-circle green" data-toggle="tooltip" data-placement="top" title="If you're a sole trader, just enter your name."></i>
                    </label>
                    <input id="company" type="text" class="form-control col-md-7" name="company" value="{{ $company->name or "" }}" placeholder="Your Company Name" required>
                </div>

                <div class="form-row py-2">
                    <label class="col-md-3 col-form-label" for="companytype">
                        Type
                    </label>

                    {!! Form::select('companyType', $companyTypes, $company->type, ['class' => 'form-control col-md-7', 'id' => 'companyType','placeholder' => 'Select', 'required']) !!}


                </div>



                <div class="form-row py-2">
                    <label class="col-md-3 col-form-label" for="address">Address</label>
                    <input id="address" type="text" class="form-control col-md-7" name="address" value="{{ $company->address or "" }}" placeholder="The company address" required>
                </div>

                <div class="form-row py-2">
                    <label class="col-md-3 col-form-label" for="city">City</label>
                    <input id="city" type="text" class="form-control col-md-7" name="city" value="{{ $company->city or "" }}" placeholder="Your Company City" required>
                </div>
                <div class="form-row py-2">
                    <label class="col-md-3 col-form-label" for="country">Country</label>
                    @include('backend.settings.countries', [ 'id' => 'country', 'class' => 'form-control col-md-7', 'default' => $company->country_residence])
                </div>
                <div class="form-row py-2">
                    <label class="col-md-3 col-form-label" for="city">Your Color</label>
                    <div id="color-picker-component" class="input-group colorpicker-component col-md-7 p-0">
                        <input type="text" value="{{ $company->color or "#006600" }}" class="form-control  col-md-12" name="color"/><span class="input-group-addon"><i></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-10 float-left text-left pt-5 pl-0">
                        <button type="submit" class="btn btn-classic">
                            <i class="fas fa-save"></i> Save Settings
                        </button>
                    </div>
                </div>
            </div>


    </div>
    <div class="col-md-6">
        <h4>Legal Representative
            <i class="fas fa-info-circle green" data-toggle="tooltip" data-placement="top" title="This information is required for a payment out."></i>
        </h4>
        <div class="form-row py-2">
            <label class="col-md-3 col-form-label" for="firstname">
                Firstname
                <i class="fas fa-info-circle green" data-toggle="tooltip" data-placement="top" title="Name of company’s legal representative person"></i>
            </label>
            <input id="firstname" type="text" class="form-control col-md-7" name="firstname" value="{{ $company->firstname or "" }}" placeholder="Firstname" required>
        </div>

        <div class="form-row py-2">
            <label class="col-md-3 col-form-label" for="address">Lastname</label>
            <input id="lastname" type="text" class="form-control col-md-7" name="lastname" value="{{ $company->lastname or "" }}" placeholder="Lastname" required>
        </div>

        <div class="form-row py-2">
            <label class="col-md-3 col-form-label" for="birthday">
                Birthday
                <i class="fas fa-info-circle green" data-toggle="tooltip" data-placement="top" title="Birthday of company’s legal representative person"></i>
            </label>
            <input type="date" name="birthday" min="1000-01-01" max="3000-12-31" class="form-control col-md-7" value="{{  $company->birthday or "" }}">
        </div>
        <div class="form-row py-2">
            <label class="col-md-3 col-form-label" for="nationality">Nationality</label>
            @include('backend.settings.countries', [ 'id' => 'nationality', 'class' => 'form-control col-md-7', 'default' => $company->country_nationality])
        </div>
        <br><br>
        @include('ajax_upload')
    </div>

</div>

</form>
