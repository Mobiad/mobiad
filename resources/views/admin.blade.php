@extends('layouts.one')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <nav class="navbar navbar-light">
                <a class="btn btn-primary" href="{{ route('allcustomer-export') }}">
                    {{ __('Download All Data') }}
                </a>

                <form method="POST" action="{{ route('export-from')}}" class="d-none">
                    @csrf
                    <div class="form-">
                        <label for="daterange">Export from - to</label>
                        <div id="reportrange"
                            style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; border-radius: 3px; width: 100%;">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span>
                            <i class="fa fa-caret-down"></i>
                        </div>
                        <input type="hidden" name="from" />
                        <input type="hidden" name="to" />
                    </div>
                    <button type="submit" class="btn btn-primary position-absolute"
                        style="right: -4em; top: 2.7em;">{{ __('Export') }}</button>
                </form>
            </nav>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Fullname</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Business Name</th>
                        <th scope="col">Business Description</th>
                        <th scope="col">Subscription Period</th>
                        <th scope="col">Subscription Numbers</th>
                        <th scope="col">Tune Status</th>
                        <th scope="col">Voice</th>
                        <th scope="col">Ref #</th>
                        <th scope="col">Created At</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers->all() as $customer)
                    <tr>
                        <th scope="row">
                            <b-button {{'v-b-toggle.accordion-'.$loop->iteration}} variant="primary" size="sm">
                                <b-icon icon="arrows-angle-expand" aria-hidden="true"></b-icon>
                            </b-button>
                        </th>
                        <td>{{$customer->fullname}}</td>
                        <td>{{$customer->phone}}</td>
                        <td>{{$customer->businessname}}</td>
                        <td>
                            <p class="d-inline-block text-truncate" style="max-width: 150px;">
                                {{$customer->businessdesc}}</p>
                        </td>
                        <td>{{$customer->subscription_period}}</td>
                        <td>
                            <ul>
                                @foreach (json_decode($customer->subscriber_numbers) as $ad)
                                <li>
                                    {{$ad->value}}
                                </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{$customer->tune ? "Yes":"No"}}</td>
                        <td>{{$customer->voice}}</td>
                        <td>{{$customer->ref}}</td>
                        <td>{{ $customer->created_at}}</td>
                        <td>
                            <form method="POST" action="{{ route('customer-export')}}">
                                @csrf
                                <input type="hidden" value="{{$customer->id}}" name="id" />
                                <b-button type="submit" variant="primary" size="sm">Download</b-button>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-0" colspan="10">
                            <b-collapse id="accordion-{{$loop->iteration}}" class="mt-2" accordion="my-accordion">
                                <b-card>
                                    <p class="card-text">Business Description (Script)</p>
                                    <b-card>
                                        {{$customer->businessdesc}}
                                    </b-card>
                                </b-card>
                            </b-collapse>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-12">
            {!! $customers->links() !!}
        </div>
    </div>
</div>
@endsection
