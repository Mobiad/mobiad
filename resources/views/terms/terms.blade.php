<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .page-break {
            page-break-after: always;
        }
    </style>

</head>

<body>
<div>
    <h2 style="color: #000000">Customer Primary Information</h2>

    <table>
        <tr>
            <td  style="padding: 8px"> Name/Jina: </td>
            <td  style="padding: 8px"> <u>{{ $customer->fullname }}</u></td>
        </tr>

        <tr>
            <td  style="padding: 8px">Phone Number / Namba ya Simu: </td>
            <td  style="padding: 8px"><u>{{ $customer->phone }}</u> </td>
        </tr>

        <tr>
            <td  style="padding: 8px">Business Name/Jina la Biashara: </td>
            <td  style="padding: 8px"> {{ $customer->businessname }} </td>
        </tr>

        <tr>
            <td  style="padding: 8px">Business Description (SCRIPT) / Maelezo ya Biashara </td>
            <td  style="padding: 8px">{{ $customer->businessdesc }}</td>
        </tr>

        <tr>
            <td  style="padding: 8px">Service Period/ Miezi ya Huduma </td>
            <td  style="padding: 8px"> {{ $customer->subscription_period }} Months</td>
        </tr>

        <tr>
            <td  style="padding: 8px">Include Tune Production / Kutengeneza Muito </td>
            <td  style="padding: 8px"> {{ $customer->tune?"YES":"NO" }}</td>
        </tr>


        <tr>
            <td  style="padding: 8px">Audio voice over / Sauti ya Muito </td>
            <td  style="padding: 8px"> {{ $customer->voice }}</td>
        </tr>
    </table>



    <div class="page-break"></div>

    <h2 style="color: #000000">SERVICE AGREEMENT</h2>
    <p>
        This  ServiceAgreement(the  "agreement') is  a  legal agreement  between you <u>{{ $customer->fullname }}</u>
        ("Client") and MobiAd Africa Tanzania Limited ("MobiAd").
        All the terms must be read and agreed prior to subscribing to the service.
        A signed agreement or confirmation of order means that these terms have been read in full
        and the client agrees to be bound by these terms. If you are agreeing on behalf of another party,
        you represent that you have authority to do so.
    </p>

    <p4>Nature of Service</p4>

    <p>MobiAd service offers mobile phone
        subscribers a custom caller tune for mobile phone numbers. The service is available
        TIGO Tanzania network subscribers</p>

    <p4>Service Terms </p4>
    <p>The service is subscribed on monthlybasis.
        The client is subscribing to the service

        from <u> .......</u>
        to <u>........</u>
        being a period of <u> {{ $customer->subscription_period }} </u> months.
        Upon subscription of the service the client can make changes to the custom tune as the client deems fit on a monthly basis.    </p>
    <p> </p>


    <p>MobiAd reserves the right to reject offering the service. This includes, but is not limited to,
        political tunes and tune that might spark religious or tribal conflicts,
        any content that is solely owned by other parties, any abusive content
        or any content or product or service that is illegal and against laws
        of United Republic of Tanzania. </p>


    <p>In case of any Huddle towards attaining the service such as DND status on the client Number,
        the client agrees to remove the huddle thus proceeding with the service </p>

    <p>Hereafter the roles of each party being </p>

    <h3>The client</h3>

    <p>To register and subscribe phone number(s) for the service and timely communication
        on de registration of phone number(s) </p>
    <p>To ensure all registered numbers are well informed about the service being subscribed to
    </p>
    <p>To provide information to be used in generating the custom tune or provide a
        recorded custom tune audio in WAV or MP3 format </p>

    <p>To provide information to be used in generating the custom tune or provide
        a recorded custom tune audio in WAV or MP3 format </p>

    <p>To ensure payments are made on time through the recommended payment platform
    </p>

    <h3>MobiAd</h3>
    <p>To offer service based on client subscription request </p>
    <p> To offer timely communication on service status or any technical issues
        that May arise which are beyond MobiAd control</p>

    <p4>Payment terms </p4>

    <p>All payments should be made through the cash, cheque or direct deposit into the company account </p>
    <p>All services payments will be done monthly </p>
    <p>All payments should be made immediately after the signing of this contract </p>

    <p> Cancellations or changes in the service (tune) are accepted only in writings and must be
        communicated 30 days prior to avoid being billed.</p>

    <p4>Termination clause </p4>
    <p> This agreement is for the period stated under service terms and will result to an expiry.
    </p>

    <p>On after the specified period. Upon will of termination, the client will issue a notice to
        MobiAd on the intention to terminate the service. With this MobiAd will have 7 days to
     Effect the termination by removing all the caller tunes loaded on the clien shared numbers.
        However, the client will be required to pay in full monthly contract amount for the
        number within the month that the contract notice is issued.
    </p>
    <p>Any notice under this agreement shall be in writing and NOT ORAL at any circumstances.
        The Notice will be sufficiently served if it is sent to an official address of The other party. </p>


    <p4>Disputes resolution </p4>

    <p>Dispute and differences concerning this agreement shall be settled by the parties amicably and in
        the event of the failure to conclude a settlement within (2) two months the </p>

    <p>Same shall be settled in accordance with the Laws of the united republic of
        Tanzania that govern this contract, especially through Mediation and Arbitration. </p>

    <p> </p>


    <div class="page-break"></div>

    <p style="padding-top: 16px;text-align: left">Accepted this on: <b><u>{{$date}}</u></b>  </p>


    <table>
        <thead>
        <tr>
            <th  style="padding: 8px; text-align: left">Subscriber Name </th>
            <th  style="padding: 8px; text-align: left">Phone </th>
            <th  style="padding: 8px; text-align: left"> Acceptance Signature Code</th>
        </tr>
        </thead>
        @foreach($phones as $key=>$phone)
            <tr>
                <td  style="padding: 8px">{{ $key+1 }}. {{ $phone->name }} </td>
                <td  style="padding: 8px">{{ $phone->phone }} </td>
                <td  style="padding: 8px"> {{ $phone->otp }}</td>
            </tr>
        @endforeach

    </table>

    <table style="margin-top: 16px">
        <tr>
            <th  style="padding: 12px;text-align: left" colspan="2">MobiAd Representative</th>
        </tr>


        <tr>
            <td  style="padding: 12px">Name</td>
            <td  style="padding: 12px">RUMISHO SHIKONYI </td>
        </tr>

        <tr>
            <td  style="padding: 12px">Signature</td>
            <td  style="padding: 12px"> </td>
        </tr>


    </table>



    </div>
</body>

</html>
<script>
    import Table from "../../js/components/Table";
    export default {
        components: {Table}
    }
</script>