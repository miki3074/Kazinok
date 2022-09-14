@extends('admin/layout')

@section('content')
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="row gap-20 masonry pos-r">
                <div class="masonry-sizer col-md-6"></div>
                <div class="masonry-item col-md-6">
                    <div class="bgc-white p-20 bd">
                        <h6 class="c-grey-900">Создать бота</h6>
                        <div class="mT-30">
                            <form action="/admin/bots/create" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Имя</label>
                                    <input type="text" name="username" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
