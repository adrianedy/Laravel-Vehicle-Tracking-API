<!DOCTYPE html>
<html>
<head>
    <title>Print {{ $device->device_id }} Device - MobiGPS</title>
</head>
<style>
    td {
        padding-bottom: 15px;
    }
</style>
<body>
    <table>
        <tr>
            <td>Device ID</td>
            <td>:</td>
            <td>{{ $device->device_id }}</td>
        </tr>
        <tr>
            <td>Device Name</td>
            <td>:</td>
            <td>{{ $device->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>Device License</td>
            <td>:</td>
            <td>{{ $device->license ?? '-'  }}</td>
        </tr>
        <tr>
            <td>Device Type</td>
            <td>:</td>
            <td>{{ $device->text_type ?? '-'  }}</td>
        </tr>
        <tr>
            <td>Created At</td>
            <td>:</td>
            <td>{{ date('g:i A / d-M-Y', strtotime($device->created_at)) }}</td>
        </tr>
        <tr>
            <td>Barcode</td>
            <td>:</td>
            <td>
                <span class='d-sm-block'>
                    <img src="data:image/png;base64,{{ $barcode }}" alt='barcode'/>
                </span>
            </td>
        </tr>
    </table>
</body>
</html>