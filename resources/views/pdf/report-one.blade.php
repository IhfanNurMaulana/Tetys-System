<html>
<head>
    <title>Laporan Saldo Kelas</title>
    <style type="text/css">
        h2 { text-align: center; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table.bordered th,
        table.bordered td {
            border: 1px solid #000;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h2>Laporan Saldo Kelas</h2>
    <table class="bordered">
        <tr>
            <th>No</th>
            <th>Nama Rombel</th>
            <th>Saldo</th>
        </tr>
        @foreach($reportdata as $data)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $data->grade_name }}</td>
            <td align="right">{{ $data->total_balance }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
