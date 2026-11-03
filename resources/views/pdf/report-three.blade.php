<html>
<head>
    <title>Laporan Transaksi</title>
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
    <h2>Laporan Transaksi</h2>
    <h3>Tanggal {{ $start }} s/d {{ $end }}</h3>
    <table class="bordered">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Setoran</th>
            <th>Penarikan</th>
            <th>Saldo</th>
        </tr>
        @foreach($reportdata as $data)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ \Carbon\Carbon::parse($data->transaction_date)->isoFormat('D MMMM Y') }}</td>
            <td>{{ $data->name }}</td>
            <td>{{ $data->grade_name }}</td>
            <td align="right">{{ $data->deposit }}</td>
            <td align="right">{{ $data->withdrawal }}</td>
            <td align="right">{{ $data->balance }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
