@extends('layouts.admin-layout')

@section('page_title', 'Money Savings | Dashboard')

@section('content')
    <div class="row row-cards">
        <div class="col-12 col-sm-12 col-lg-4">
            <div class="card">
                <div class="card-body p-3 text-center">
                    @if (isset($savings->savingHistories()->latest('id')->first('is_increase')->is_increase) && $savings->savingHistories()->latest('id')->first('is_increase')->is_increase)
                        <div class="text-right text-green">
                            {{ $savings->saving_percentage }}%
                            <i class="fe fe-chevron-up"></i>
                        </div>
                    @else
                        <div class="text-right text-danger">
                            {{ $savings->saving_percentage }}%
                            <i class="fe fe-chevron-down"></i>
                        </div>
                    @endif

                    <div class="h1 m-0">IDR {{ number_format($totalSaving) }}</div>
                    <div class="text-muted mb-4">Total Duit Terkumpul</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-lg-4">
            <div class="card">
                <div class="card-body p-3 text-center">
                    <div class="text-right text-green">
                        &nbsp;
                    </div>

                    <div class="h1 m-0">
                        IDR {{ number_format(\Illuminate\Support\Facades\Config::get('constants.targetBalance') - $totalSaving) }}</div>
                    <div class="text-muted mb-4">Sisa Dari Target</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-lg-4">
            <div class="card">
                <div class="card-body p-3 text-center">
                    <div class="text-red d-flex justify-content-end align-items-center">
                        <i class="fe fe-info mr-1"></i>
                        <small>Jangan lupa balikin!</small>
                    </div>
                    <div class="h1 m-0">IDR {{ count($savingHistory) ? number_format($savingHistory->where('type', 'expense')->sum('amount')) : 0 }}</div>
                    <div class="text-muted mb-4">Total Duit Diambil</div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Keterangan Ngambil Duit</h3>
                </div>
                <div id="chart-development-activity" style="height: 10rem"></div>
                <div class="table-responsive">
                    <table class="table card-table table-striped table-vcenter">
                        <thead>
                        <tr>
                            <th colspan="2">Nama</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($savingHistory->filter(function ($item) { return $item->type == 'expense'; }) as $key => $item)
                            @if($key <= 5)
                                <tr>
                                    <td class="w-1"><span class="avatar"
                                                          style="background-image: url({{ asset('assets/images/user/user-' . $item->savings->user->id . '.jpg') }})"></span></td>
                                    <td>{{ explode(' ', trim($item->savings->user->name))[0] }}</td>
                                    <td>{{ $item->note ?: '-' }}</td>
                                    <td>IDR {{ number_format($item->amount) }}</td>
                                    <td class="text-nowrap">{{ date('d/m/Y') }}</td>
                                    <td class="w-1 text-danger"><i class="fe fe-info"></i></td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <script>
                require(['c3', 'jquery'], function (c3, $) {
                    $(document).ready(function () {
                        var chart = c3.generate({
                            bindto: '#chart-development-activity', // id of chart wrapper
                            data: {
                                columns: [
                                    // each columns data
                                    ['data1', {{ join(', ', $savingHistory->map(function ($item) { return $item->type == 'revenue' ? $item->amount : - $item->amount; })->toArray()) }}],
                                ],
                                type: 'area', // default type of chart
                                groups: [
                                    ['data1', 'data2', 'data3']
                                ],
                                colors: {
                                    'data1': tabler.colors["blue"]
                                },
                                names: {
                                    // name of each serie
                                    'data1': 'Jumlah Nabung'
                                }
                            },
                            axis: {
                                y: {
                                    padding: {
                                        bottom: 0,
                                    },
                                    show: false,
                                    tick: {
                                        outer: false
                                    }
                                },
                                x: {
                                    padding: {
                                        left: 0,
                                        right: 0
                                    },
                                    show: false
                                }
                            },
                            legend: {
                                position: 'inset',
                                padding: 0,
                                inset: {
                                    anchor: 'top-left',
                                    x: 20,
                                    y: 8,
                                    step: 10
                                }
                            },
                            tooltip: {
                                format: {
                                    title: function (x) {
                                        return '';
                                    }
                                }
                            },
                            padding: {
                                bottom: 0,
                                left: -1,
                                right: -1
                            },
                            point: {
                                show: false
                            }
                        });
                    });
                });
            </script>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
            <div class="alert alert-primary">
                Hemat hemat dulu yaa ! <strong>Kurangin jajan dan maen sampe nikah</strong>.
            </div>
            <div class="row">
                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Statistik</h3>
                        </div>
                        <div class="card-body">
                            <div id="chart-donut" style="height: 12rem;"></div>
                        </div>
                    </div>
                    <script>
                        require(['c3', 'jquery'], function (c3, $) {
                            $(document).ready(function () {
                                var chart = c3.generate({
                                    bindto: '#chart-donut', // id of chart wrapper
                                    data: {
                                        columns: [
                                            // each columns data
                                            ['data1', {{ $savingHistory->where('savings_id', 1)->sum('amount') }}],
                                            ['data2', {{ $savingHistory->where('savings_id', 2)->sum('amount') }}]
                                        ],
                                        type: 'donut', // default type of chart
                                        colors: {
                                            'data1': tabler.colors["green"],
                                            'data2': tabler.colors["green-light"]
                                        },
                                        names: {
                                            // name of each serie
                                            'data1': 'Aji',
                                            'data2': 'Syifa'
                                        }
                                    },
                                    axis: {},
                                    legend: {
                                        show: false, //hide legend
                                    },
                                    padding: {
                                        bottom: 0,
                                        top: 0
                                    },
                                });
                            });
                        });
                    </script>
                </div>
                <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Pembagian Biaya</h3>
                        </div>
                        <div class="card-body">
                            <div id="chart-pie" style="height: 12rem;"></div>
                        </div>
                    </div>
                    <script>
                        require(['c3', 'jquery'], function (c3, $) {
                            $(document).ready(function () {
                                var chart = c3.generate({
                                    bindto: '#chart-pie', // id of chart wrapper
                                    data: {
                                        columns: [
                                            // each columns data
                                            ['data1', 20],
                                            ['data2', 10],
                                            ['data3', 50],
                                            ['data4', 20]
                                        ],
                                        type: 'pie', // default type of chart
                                        colors: {
                                            'data1': tabler.colors["blue-darker"],
                                            'data2': tabler.colors["blue"],
                                            'data3': tabler.colors["blue-light"],
                                            'data4': tabler.colors["blue-lighter"]
                                        },
                                        names: {
                                            // name of each serie
                                            'data1': 'Mahar (Aji)',
                                            'data2': 'Biaya Lamaran',
                                            'data3': 'Biaya Nikah',
                                            'data4': 'Seserahan'
                                        }
                                    },
                                    axis: {},
                                    legend: {
                                        show: false, //hide legend
                                    },
                                    padding: {
                                        bottom: 0,
                                        top: 0
                                    },
                                });
                            });
                        });
                    </script>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="h5">Penabung Paling Banyak</div>
                            <div class="display-4 font-weight-bold mb-4">
                                @if($savingHistory->where('savings_id', 1)->sum('amount') >  $savingHistory->where('savings_id', 2)->sum('amount'))
                                    Aji
                                @else
                                    Syifa
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="h5">Jumlah Nabung Terakhir</div>
                            <div class="display-4 font-weight-bold mb-4">
                                {{ $savings->savingHistories->first() ? number_format($savings->savingHistories->where('type', 'revenue')->sortByDesc('id')->first()->amount) : 0 }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row">
                @foreach(\App\Models\Savings::get() as $key => $item)
                    <div class="col-12 col-lg-3">
                        <div class="card card-sm {{ $item->savingHistories()->latest('id')->first()->is_increase ? 'bg-green-lightest' : 'bg-red-lightest' }}">
                            <div class="card-body">
                                <div class="row align-items-center position-relative">
                                    <div class="position-absolute" style="top: -40px; left: 0">
                                        <img src="{{ asset('assets/images/user/user-' . $item->id . '.jpg') }}" alt="" class="avatar shadow-lg border border-dark">
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            <span class="font-weight-bold">IDR {{ number_format($item->total_balance) }}</span>
                                            <span class="float-right font-weight-medium {{ $item->savingHistories()->latest('id')->first()->is_increase ? 'text-green' : 'text-danger' }}">
                                            @if($item->savingHistories()->latest('id')->first()->is_increase)
                                                    <i class="fe fe-trending-up"></i>
                                                @else
                                                    <i class="fe fe-trending-down"></i>
                                                @endif

                                                {{ $item->savingHistories()->latest('id')->first()->saving_rate }}%
                                        </span>
                                        </div>
                                        <div>
                                            <small class="text-muted">Terbaru: {{ number_format($item->savingHistories()->latest('id')->first()->amount) }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-12 col-sm-12 col-lg-6 d-flex justify-content-center align-items-center mb-5">
                    <button type="button" class="btn btn-primary btn-pill shadow-lg mr-2" data-toggle="modal" data-target="#exampleModal">
                        <span><i class="fe fe-plus-circle mr-1"></i></span> Nabung Catet Woee!
                    </button>

                    <a href="{{ route('list_saving') }}" type="button" class="btn btn-info btn-pill text-white shadow-lg">
                        <span><i class="fe fe-eye mr-1"></i></span> Lihat Data Lengkap
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Tabungan</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap">
                        <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Nama</th>
                            <th>Kredit</th>
                            <th>Debit</th>
                            <th>Persentase</th>
                            <th>Performa</th>
                            <th>Tanggal</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($savingHistory as $key => $item)
                                <tr>
                                    <td class="text-muted">{{ $key + 1 }}</td>
                                    <td>{{ $item->savings->user->name }}</td>
                                    <td>{{ $item->type == 'revenue' ? 'IDR ' . number_format($item->amount) : '-' }}</td>
                                    <td>{{ $item->type == 'expense' ? 'IDR ' . number_format($item->amount) : '-' }}</td>
                                    <td>{{ $item->saving_rate }}%</td>
                                    <td>
                                        @if ($item->is_increase)
                                            <p class="text-success">
                                                <i class="fe fe-trending-up h-4"></i>
                                            </p>
                                        @else
                                            <p class="text-warning">
                                                <i class="fe fe-trending-down h-4"></i>
                                            </p>
                                        @endif
                                    </td>
                                    <td>{{ date('l, d F Y') }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-primary font-weight-bold">
                                <td colspan="2">Total</td>
                                <td>IDR {{ number_format(\App\Models\SavingHistory::where('type', 'revenue')->sum('amount') - \App\Models\SavingHistory::where('type', 'expense')->sum('amount')) }}</td>
                                <td>IDR {{ number_format(\App\Models\SavingHistory::where('type', 'expense')->sum('amount')) }}</td>
                                <td colspan="3"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"><i class="fe fe-dollar-sign text-success mr-1"></i>Tabungin Yang Banyak</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin_add_saving') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-auto">
                                            <span class="avatar avatar-xl"
                                                  style="background-image: url({{ asset('assets/images/user/user-' . auth()->user()->id . '.jpg') }})"></span>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label class="form-label">Nabung/Ngambil Berapa ?</label>
                                                <input name="savingAmount" class="form-control" type="number" placeholder="IDR 0"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Ngapain ?</label>
                                        <div class="selectgroup selectgroup-pills">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="typeActivity" value="1" class="selectgroup-input" checked="">
                                                <span class="selectgroup-button selectgroup-button-icon"><i class="fe fe-sun mr-2"></i> Nabung biar cerah!</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="typeActivity" value="2" class="selectgroup-input">
                                                <span class="selectgroup-button selectgroup-button-icon"><i class="fe fe-cloud-rain mr-2"></i> Duit sedih dibuang mulu.</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control" rows="5"></textarea>
                                    </div>

                                    <div class="form-footer">
                                        <button type="submit" class="btn btn-primary btn-block">Save</button>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
