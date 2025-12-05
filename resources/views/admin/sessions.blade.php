@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Authentication Sessions</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Session ID</th>
                <th>User</th>
                <th>IP Address</th>
                <th>User Agent</th>
                <th>Last Activity</th>
                <th>Payload</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $row)
            <tr>
                <td>{{ $row['id'] }}</td>
                <td>
                    @if($row['user'])
                        <a href="{{ route('admin.customers.index') }}?user_id={{ $row['user']->id }}">{{ $row['user']->name ?? $row['user']->email }}</a>
                        <div class="text-muted">{{ $row['user']->email }}</div>
                    @else
                        <span class="text-muted">(guest)</span>
                    @endif
                </td>
                <td>{{ $row['ip_address'] }}</td>
                <td style="max-width:280px;word-break:break-word">{{ $row['user_agent'] }}</td>
                <td>{{ $row['last_activity'] }}</td>
                <td style="max-width:300px;word-break:break-word">{{ $row['payload_preview'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
