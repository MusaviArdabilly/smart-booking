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

Your Assessment details:

<table class="table">
    <tr>
        <td>ID</td>
        <td>:</td>
        <td>{{ $maildata['id'] }}</td>
    </tr>
    <tr>
        <td>Point</td>
        <td>:</td>
        <td>{{ $maildata['point'] }}</td>
    </tr>
    <tr>
        <td>created at</td>
        <td>:</td>
        <td>{{ $maildata['created_at'] }}</td>
    </tr>
    <tr>
        <td>Expires at</td>
        <td>:</td>
        <td>{{ $maildata['expires_at'] }}</td>
    </tr>
</table>

<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
