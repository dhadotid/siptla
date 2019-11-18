<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rekomendasi Temuan</title>
</head>
<body onload="window.print()" onfocus="window.close()">
    <h3>Rekomendasi Temuan Hasil Audit Kinerja</h3>
    <table cellspacing="0" cellpadding="5" width="100%" border="1">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Rekomendasi Temuan</th>
                <th class="text-center">Kode</th>
                <th class="text-center">Jumlah Kejadian</th>
                <th class="text-center">Persentase</th>
                <th class="text-center">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalkerugian = 0;
                $totalpersen = 0;
            @endphp
            @foreach ($rekom as $key => $item)
                <tr>
                    <td>{{ $key = $key + 1 }}</td>
                    <td>{{ $item->rekomendasi }}</td>
                    <td style="text-align:center;">{{ $item->code }}</td>
                    <td style="text-align:center;">
                        @php
                            $countkejadian = 0;
                        @endphp
                        @foreach ($kejadian as $k)
                            @if ($k->rekomendasi_id==$item->id)
                                @php
                                    $countkejadian = $k->jumlah_kejadian
                                @endphp
                                @break
                            @endif
                        @endforeach
                        {{ $countkejadian }}
                    </td>
                    <td style="text-align:center;">
                        @if ($totalkejadian!=0)
                            @php
                                $persen = round($countkejadian/$totalkejadian*100, 2)
                            @endphp
                            {{ $persen }} %
                            @php
                                $totalpersen += $persen;
                            @endphp
                        @else
                            0
                        @endif
                    </td>
                    <td style="text-align:right;">
                        @php
                            $countkerugian = 0;
                        @endphp
                        @foreach ($nilai as $k)
                            @if ($k->rekomendasi_id==$item->id)
                                @php
                                    $countkerugian = $k->nilai_kerugian
                                @endphp
                                @break
                            @endif
                        @endforeach
                        {{ number_format($countkerugian, 0) }}
                        @php
                            $totalkerugian += $countkerugian;
                        @endphp
                    </td>
                </tr>
            @endforeach
                <tr>
                    <td colspan="3" style="text-align:right;">Jumlah &nbsp;&nbsp;&nbsp;</td>
                    <td style="text-align:center;">{{ $totalkejadian }}</td>
                    <td style="text-align:center;">{{ round($totalpersen, 1) }} % <br> <i>(Pembulatan)</i></td>
                    <td style="text-align:right;">{{ number_format($totalkerugian, 0) }}</td>
                </tr>
        </tbody>
    </table>
</body>
</html>