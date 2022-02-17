@extends('layouts.admin.app')

@section('content')

    <div>
        <h2>@lang('site.home')</h2>
    </div>

    <ul class="breadcrumb mt-2">
        <li class="breadcrumb-item">@lang('site.home')</li>
    </ul>

    {{--row top statsfic--}}
    <div class="row">

        {{--card genres--}}
        <div class="col-md-4">
            <div class="tile shadow">
                <div class="d-flex justify-content-between">
                    <i class="fa fa-list"> @lang('genres.genres')</i>
                    <a href="{{route('admin.genres.index')}}">@lang("site.show_all")</a>
                </div>

                <div class="loader loader-sm"></div>
                <h2 class="mt-3" style="display: none" id="count-genres"></h2>
            </div>
        </div>{{--end of col-md-4 --}}

        {{--card movies--}}
        <div class="col-md-4">
            <div class="tile shadow">
                <div class="d-flex justify-content-between">
                    <i class="fa fa-film"> @lang('movies.movies')</i>
                    <a href="{{route('admin.movies.index')}}">@lang("site.show_all")</a>
                </div>

                <div class="loader loader-sm"></div>
                <h2 class="mt-3" style="display: none" id="count-movies"></h2>
            </div>
        </div>{{--end of col-md-4 --}}

        {{--card actors--}}
        <div class="col-md-4">
            <div class="tile shadow">
                <div class="d-flex justify-content-between">
                    <i class="fa fa-address-book-o"> @lang('actors.actors')</i>
                    <a href="{{route('admin.actors.index')}}">@lang("site.show_all")</a>
                </div>

                <div class="loader loader-sm"></div>
                <h2 class="mt-3" style="display: none" id="count-actors"></h2>
            </div>
        </div>{{--end of col-md-4 --}}

    </div>{{--end of first row--}}

    {{--row chart--}}
    <div class="row">
        <div class="col-md-12">
            <div class="tile shadow ">
                <div class="d-flex justify-content-between">
                    <h3 class="ml-3">@lang('movies.movies_chart')</h3>
                    <select id="select_year_chart" style="width: 100px;">
                        @for($i = 5 ; $i >= 0 ; $i--)
                            <option value="{{now()->subYears($i)->year}}" {{now()->subYears($i)->year == now()->year ? "selected" : ""}} >{{now()->subYears($i)->year}}</option>
                        @endfor
                    </select>
                </div>
                <div id="movies_chart_wrapper">
                </div>
            </div>
        </div>
    </div>{{--end of last row--}}

    {{--row top movies--}}
    <div class="row">
        {{--popular movies--}}
        <div class="col-md-12">
            <div class="tile shadow">
                <h3 class="ml-3">@lang('movies.top') @lang('movies.popular')</h3>
                <table class="mt-4 table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">@lang('movies.title')</th>
                            <th scope="col">@lang('movies.vote')</th>
                            <th scope="col">@lang('movies.vote_count')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($popularMovies as $index => $movie)
                                <tr>
                                    <th scope="row">{{$index + 1}}</th>
                                    <td>
                                        <a href="{{route('admin.movies.index',['movie_id' => $movie->e_id ])}}">
                                            {{$movie->title}}
                                        </a>
                                    </td>
                                    <td><i class="fa fa-star" style="color: yellow" ></i> {{$movie->vote}}</td>
                                    <td>{{$movie->vote_count}}</td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{--now playing movies--}}
        <div class="col-md-12">
            <div class="tile shadow">
                <h3 class="ml-3">@lang('movies.top') @lang('movies.now_playing')</h3>
                <table class="mt-4 table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('movies.title')</th>
                        <th scope="col">@lang('movies.vote')</th>
                        <th scope="col">@lang('movies.vote_count')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($nowPlayingMovies as $index => $movie)
                        <tr>
                            <th scope="row">{{$index + 1}}</th>
                            <td>
                                <a href="{{route('admin.movies.index',['movie_id' => $movie->e_id ])}}">
                                    {{$movie->title}}
                                </a>
                            </td>
                            <td><i class="fa fa-star" style="color: yellow" ></i> {{$movie->vote}}</td>
                            <td>{{$movie->vote_count}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{--upComing movies--}}
        <div class="col-md-12">
            <div class="tile shadow">
                <h3 class="ml-3">@lang('movies.top') @lang('movies.upComingMovies')</h3>
                <table class="mt-4 table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('movies.title')</th>
                        <th scope="col">@lang('movies.vote')</th>
                        <th scope="col">@lang('movies.vote_count')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($upComingMovies as $index => $movie)
                        <tr>
                            <th scope="row">{{$index + 1}}</th>
                            <td>
                                <a href="{{route('admin.movies.index',['movie_id' => $movie->e_id ])}}">
                                    {{$movie->title}}
                                </a>
                            </td>
                            <td><i class="fa fa-star" style="color: yellow" ></i> {{$movie->vote}}</td>
                            <td>{{$movie->vote_count}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>{{--end of thred row--}}

@endsection

@push('scripts')
    <script>
        $(function (){
            //count
            countGenres();
            moviescharter({{ now()->year }});

            //get year then send to charts
            $('#select_year_chart').on('change',function () {
                let year = $(this).find(':selected').val();
                moviescharter(year);
            });

        });//end of function

        function countGenres(){
            $.ajax({
                type: 'get',
                url: "{{route('admin.home.topStatistic')}}",
                success:function(data) {
                    $(".loader-sm").hide();
                    $('#count-genres').show().text(data.countGenres);
                    $('#count-movies').show().text(data.countMovies);
                    $('#count-actors').show().text(data.countActors);
                }
            });
        }

        function moviescharter(year){

            let loader =
                `<div class="d-flex justify-content-center align-items-center">
                        <div class="loader loader-md"></div>
                    </div>`;

            $('#movies_chart_wrapper').empty().append(loader);

            $.ajax({
                url: "{{ route('admin.home.moviesChart') }}",
                data: {
                    'year': year,
                },
                cache:false,
                success: function (html) {
                    console.log(html);
                    $('#movies_chart_wrapper').empty().append(html);
                }
            });
        }
    </script>
@endpush
