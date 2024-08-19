@extends('admin.layouts.main')

@section('admin-content')
    <div class="my-3 row row-cards">
        <div class="col-sm-6 col-lg-4">
            <div class="card card-md">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-blue text-white avatar">
                                <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <desc>Download more icon variants from https://tabler-icons.io/i/currency-dollar</desc>
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path
                                        d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2">
                                    </path>
                                    <path d="M12 3v3m0 12v3"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium display-7">
                                No of Artist
                            </div>
                            <div class="text-muted">
                                No:- {{ $data['no_of_artist'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card card-md">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-yellow text-white avatar">
                                <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <desc>Download more icon variants from https://tabler-icons.io/i/shopping-cart</desc>
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="6" cy="19" r="2"></circle>
                                    <circle cx="17" cy="19" r="2"></circle>
                                    <path d="M17 17h-11v-14h-2"></path>
                                    <path d="M6 5l14 1l-1 7h-13"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium display-7">
                                No of Musics
                            </div>
                            <div class="text-muted">
                                No:- {{ $data['no_of_musics'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-4">
            <div class="card card-md">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-green text-white avatar">
                                <!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <desc>Download more icon variants from https://tabler-icons.io/i/users</desc>
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    <path d="M21 21v-2a4 4 0 0 0 -3 -3.85"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium display-7">
                                Total Genre
                            </div>
                            <div class="text-muted">
                                No:- {{ $data['no_of_genre'] }}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- < class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Orders</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer Name</th>
                            <th>Order Date</th>
                            <th>Order Status</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['orders'] as $order)
                            <tr>
                                <td>{{$order->order_id}}</td>
                                <td>{{$order->product->name}}</td>
                                <td>{{$order->quantity}}</td>
                                <td>{{$order->status}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div> --}}
    <div class="d-flex justify-content-center align-item-center">

        <div class="card p-4 ">
            Total Music by Genere
            <div class="ct-chart">

            </div>
        </div>

        <div class="card p-5 ">
            Total Music by Genere
            <div class="linechart">

            </div>
        </div>
    </div>
@endsection

@section('custom-scripts')
    <script>
        $(document).ready(function() {
            let = chart = new Chartist.Pie('.ct-chart', {
                series: [20, 10, 30, ],
                labels: ['Bananas', 'Apples', 'Grapes'],

            }, {
                donut: true,
                donutWidth: 60,
                donutSolid: true,
                startAngle: 360,
                showLabel: true
            });
            $.ajax({
                type: "get",
                url: "{{ route('admin.dashboard') }}",
                success: function(response) {

                    response = response['total_genre_musics']
                    // console.log({response});
                    data = []
                    data['series'] = response.map((res) => res.music_count)
                    data['labels'] = response.map((res) => res.name)

                    chart.update({
                        labels: data['labels'],
                        series: data['series']
                    })

                }
            });


            var data = {
                labels: ['W1', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8', 'W9', 'W10'],
                series: [
                    [1, 2, 4, 8, 6, -2, -1, -4, -6, -2]
                ]
            };

            var options = {
                high: 10,
                low: 0,
                axisX: {
                    labelInterpolationFnc: function(value, index) {
                    return value;
                        return index % 1 === 0 ? value : null;
                    }

                },
                axisY: {
                labelInterpolationFnc: function(value) {
                    return Math.round(value); // Round to the nearest integer
                },
                scaleMinSpace: 30
            }
            };

            let line_chart = new Chartist.Bar('.linechart', data, options);
            $.ajax({
                type: "get",
                url: "{{ route('admin.dashboard') }}",

                success: function(response) {
                    response = response['top_five_artist']
                    data = []
                    data['series'] = response.map((res) => res.music_count)
                    data['labels'] = response.map((res) => res.name)

                    console.log({
                        data
                    });
                    line_chart.update({
                        labels: data['labels'],
                        series: [data['series']]
                    })
                }
            });
        });
    </script>
@endsection
