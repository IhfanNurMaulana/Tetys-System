<html>
<head>
    <title>Laporan Saldo Siswa</title>
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
    <h2>Laporan Saldo Siswa</h2>
    <h3>Kelas {{ $grade ? $grade->grade_name : '' }}</h3>
    <table class="bordered">
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Saldo</th>
        </tr>
        @foreach($reportdata as $data)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $data->name }}</td>
            <td align="right">{{ $data->balance }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
