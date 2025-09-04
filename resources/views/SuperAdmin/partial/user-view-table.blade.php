@foreach ($cdrs as $key => $value)
<tr>
    <td>{{ $key + 1 }}</td>
    <td>{{ $value->company_name }}</td>
    <td>{{ $value->master_number }}</td>
    <td>{{ $value->cc_id }}</td>
    <td>{{ $value->poc_name }}</td>
    <td>{{ $value->adress }}</td>
    <td>
        @if ($value->auto_detection)
        <a id="autoDetectionBtn{{ $value->id }}" data-status="active" class="btn btn-success btn-sm rounded" title="Active" data-bs-toggle="tooltip" data-bs-placement="top" title="Auto detection is active" data-id="{{ $value->id }}">Active</a>
        @else
        <a id="autoDetectionBtn{{ $value->id }}" data-status="inactive" class="btn btn-danger btn-sm rounded" title="Inactive" data-bs-toggle="tooltip" data-bs-placement="top" title="Auto detection is inactive" data-id="{{ $value->id }}">Inactive</a>
        @endif
    </td>
    <td>
        @if ($value->status)
        <a id="statusBtn{{ $value->id }}" data-status="active" class="btn btn-success btn-sm rounded" title="Active" data-bs-toggle="tooltip" data-bs-placement="top" title="User is active" data-id="{{ $value->id }}">Active</a>
        @else
        <a id="statusBtn{{ $value->id }}" data-status="inactive" class="btn btn-danger btn-sm rounded" title="Inactive" data-bs-toggle="tooltip" data-bs-placement="top" title="User is inactive" data-id="{{ $value->id }}">Inactive</a>
        @endif
    </td>
</tr>
@endforeach
