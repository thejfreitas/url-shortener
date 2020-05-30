<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!-- Bootstrap CSS -->
        <link
            rel="stylesheet"
            href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
            integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
            crossorigin="anonymous"
        />
        <title>Url Shortener</title>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Url Shortener</h1>

                    @if(Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action={{ route('shortener.store.link') }} class="form-inline">
                        @csrf
                        <label class="sr-only" for="url">Url</label>
                        <input type="text" name="url" class="form-control mb-2 col" id="url" placeholder="Insert your url" />

                        <button type="submit" class="btn btn-primary mb-2">
                            Shorten
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if(count($links) > 0)
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Link</th>
                                <th scope="col">Shortened</th>
                                <th scope="col">Hits</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($links as $link)
                            <tr>
                                <th scope="row">{{ $link->id }}</th>
                                <td>{{ $link->url }}</td>
                                <td><a target="_blank" href="{{ $link->code }}">{{route('shortener.index') . '/' . $link->code }}</a></td>
                                <td>{{ $link->hits }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        <span>You Don't have any shortened link yet</span>
                    @endif
                </div>
            </div>
        </div>
    </body>
</html>
