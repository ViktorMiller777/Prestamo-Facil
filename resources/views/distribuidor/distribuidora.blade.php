<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuidoras Dashboard</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    body, html {
        display:flex;
        flex-direction: column;
        gap:25px;
        padding:10px;
        width: 100%;
        height: 100%;
        background-color: #5f9885;
    }
    .box{
        border-radius:10px;
        height: 5rem;
        width: 100%;
        background:red;
    }
    .box-2{
        border-radius:10px;
        height: 5rem;
        width: 100%;
        background:pink;

    }
</style>
<body>
    <div class="box">
        <h1>
            Bienvenido al Panel
            <span style="text-transform: capitalize;">
                {{ auth()->user()->persona->nombre }}!!
            </span>
        </h1>
    </div>
    <div class="box-2">
        <div>
            
        </div>
    </div>
</body>
</html>