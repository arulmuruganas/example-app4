<html>
    <head>
        <title>Hello World</title>
    </head>
    <body>
        <h1>Hello World</h1>
        <p>{{ $type }} </p>

@error('title')
    <div class="alert alert-danger">{{ $message }}</div>
    <div class="alert alert-danger">{{ $error }}</div>
@enderror
    </body>

</html>