@component('mail::message')
<style>
    .table {
        width: 100%;
    }

    .table td {
        padding: 3px;
    }
</style>

# {{ $maildata['title'] }}

If you require any further information, please feel free to contact us.<br>

Your Booking details:

<table class="table">
    <tr>
        <td>ID</td>
        <td>:</td>
        <td>{{ $maildata['id'] }}</td>
    </tr>
    <tr>
        <td>Desk</td>
        <td>:</td>
        <td>{{ $maildata['desk'] }}</td>
    </tr>
    <tr>
        <td>Duration</td>
        <td>:</td>
        <td>{{ $maildata['duration'] }}</td>
    </tr>
    <tr>
        <td>Check In at</td>
        <td>:</td>
        <td>{{ $maildata['checkin'] }}</td>
    </tr>
    <tr>
        <td>Check Out at</td>
        <td>:</td>
        <td>{{ $maildata['checkout'] }}</td>
    </tr>
</table>

<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
