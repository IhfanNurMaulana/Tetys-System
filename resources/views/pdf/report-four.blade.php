<html>
<head>
    <title>Laporan Transaksi Siswa</title>
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
    <h2>Laporan Transaksi Siswa</h2>
    <table>
        <tr>
            <td>Nama</td>
            <td>: {{ $student ? $student->name : '' }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>: {{ $grade ? $grade->grade_name : '' }}</td>
        </tr>
        <tr>
            <td>Periode</td>
            <td>: {{ $start }} s/d {{ $end }}</td>
        </tr>
    </table><br>

    <table class="bordered">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Setoran</th>
            <th>Penarikan</th>
            <th>Saldo</th>
        </tr>
        @foreach($reportdata as $data)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ \Carbon\Carbon::parse($data->transaction_date)->isoFormat('D MMMM Y') }}</td>
            <td align="right">{{ $data->deposit }}</td>
            <td align="right">{{ $data->withdrawal }}</td>
            <td align="right">{{ $data->balance }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
