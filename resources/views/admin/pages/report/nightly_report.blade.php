<!DOCTYPE html>
<html>
<head>
    <title>Nightly Inventory Report</title>
    <style>
        body { font-family: sans-serif; }
        h2 { margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
    </style>
</head>
<body>
    <h1>Nightly Inventory Report - {{ $date }}</h1>

    @foreach($sections as $title => $data)
        <h2>{{ ucfirst(str_replace('_', ' ', $title)) }}</h2>
        @if(count($data))
            <table>
                <thead>
                    <tr>
                        @foreach(array_keys($data[0]) as $heading)
                            <th>{{ $heading }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        <tr>
                            @foreach($row as $value)
                                <td>{{ $value }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No data available.</p>
        @endif
    @endforeach
</body>
</html>
