<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <title>Sistem Pendukung Keputusan</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    </head>
    <body class="bg-gray-900">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body bg-gray-900">
                                <h2 class="text-white">Matriks Keputusan</h2>
                                <table class="table table-bordered table-striped bg-white text-center">
                                    <thead>
                                        <tr>
                                            <th>A/C</th>
                                            @foreach ($criteria as $c)
                                            <th>{{$c->nama}}</th>        
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($sc as $a => $dataAlternatif)
                                        <tr>
                                            <td>{{$a}}</td>
                                            @foreach ($dataAlternatif as $i)
                                            <td>{{$i->score}}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body bg-gray-900">
                                <h2 class="text-white">Normalisasi</h2>
                                <table id="normTable" class="display nowrap table table-striped table-bordered bg-white text-center">
                                    <thead>
                                        <tr>
                                            <th>A/C</th>
                                            @foreach ($criteria as $c)
                                            <th>{{ $c->nama }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($alternatif as $a)
                                        <tr>
                                            <td>{{$a->nama}}</td>
                                            @foreach ($normalisasi as $norm)
                                                <td>{{ round($norm[$a->nama],4) }}</td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body bg-gray-900">
                                <h2 class="text-white">Normalisasi Terbobot</h2>
                                <table id="mytable" class="display nowrap table table-striped table-bordered bg-white text-center">
                                    <thead>
                                        <tr>
                                            <th>A/C</th>
                                            @foreach ($criteria as $c)
                                            <th>{{ $c->nama }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($alternatif as $a)
                                        <tr>
                                            <td>{{$a->nama}}</td>
                                            @foreach ($normTerbobot as $norm)
                                                @foreach ($norm as $norm => $jenis)
                                                    <td>{{ round($jenis[$a->nama],4) }}</td>
                                                @endforeach
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body bg-gray-900">
                                <h2 class="text-white">Nilai Optimasi</h2>
                                <table id="mytable" class="display nowrap table table-striped table-bordered bg-white text-center">
                                    <thead>
                                        <tr>
                                            <th>Alternatif</th>
                                            <th>MAX</th>
                                            <th>MIN</th>
                                            <th>Nilai Yi</th>
                                            <th>Ranking</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($alternatif as $a)
                                        <tr>
                                            <td>{{$a->nama}}</td>
                                            <td>{{ round($sumBenefit[$a->nama],4) }}</td>
                                            <td>{{ round($sumCost[$a->nama],4) }}</td>
                                            <td>{{ round($yi[$a->nama],4) }}</td>
                                            <td>{{ ($rank[$a->nama]) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>  
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

