@extends('layouts.admin.app')

@section('content')

    <div>
        <h2>@lang('movies.movies')</h2>
    </div>

    <ul class="breadcrumb mt-2">
        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('site.home')</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.movies.index') }}">@lang('movies.movies')</a></li>
        <li class="breadcrumb-item">@lang('site.show')</li>
    </ul>

    <div class="row">

        <div class="col-md-12">

            <div class="tile shadow">

                <div class="row">

                    <div class="col-md-2">
                        {{--image--}}
                        <img src="{{$movie->poster_path}}" class="img-fluid">
                    </div>{{--end of col-ms-2--}}

                    <div class="col-md-10">
                        {{--title--}}
                        <h2>{{$movie->title}}</h2>
                        {{--genres--}}
                        @foreach($movie->genres as $genre)
                            <span class="badge badge-primary">
                                {{$genre->name}}
                            </span>
                        @endforeach
                        {{--description--}}
                        <p class="pt-3" style="font-size: 16px">{{$movie->description}}</p>
                        {{--star and vote--}}
                        <div class="d-flex">
                            <i class="fa fa-star text-warning" style="font-size: 30px"></i>
                            <h2 class="ml-2">{{$movie->vote}}</h2>
                            <p class="ml-2 mb-0 align-self-center" style="font-size: 16px">By {{$movie->vote_count}}</p>
                        </div>
                        {{--data--}}
                        <p><span class="fw-bold">@lang('movies.language') :  </span>En</p>
                        <p><span class="fw-bold">@lang('movies.release_date') :  </span>{{$movie->release_date}}</p>

                        <hr>

                        {{--desplay images--}}
                        <div class="row" id="movie_image">
                            @foreach($movie->images as $image)
                                <div class="col-md-3 my-2">
                                    <a href="{{$image->image_path}}">
                                        <img src="{{$image->image_path}}" class="img-fluid" alt="">
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        {{--desplay actors--}}
                        <div class="row">
                            @foreach($movie->actors as $actor)
                                <div class="col-md-2 my-2">
                                    <a href="{{route('admin.movies.index',['actor_id' =>$actor->id ])}}">
                                        <img src="{{$actor->image_path}}" class="img-fluid" alt="">
                                    </a>
                                </div>
                            @endforeach
                        </div>

                    </div>{{--end of col-ms-10--}}
                </div>//end of main row

            </div><!-- end of tile -->

        </div><!-- end of col -->

    </div><!-- end of row -->

@endsection

@push('scripts')

    <script>
        $(function (){
            $('#movie_image').magnificPopup({
                delegate:'a',
                type: 'image',
                gallery:{
                    enabled:true
                }
            });
        })
    </script>
@endpush


