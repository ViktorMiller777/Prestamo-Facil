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
        padding-top: 4rem !important;
        display:flex;
        flex-direction: column;
        gap:25px;
        padding:10px;
        width: 100%;
        height: 100%;
        background-color: #f4f7f6;
    }
    .box {
        display: flex;
        flex-direction: row;
        align-items: center; 
        justify-content: space-between;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        gap: 40px;
        padding: 0 20px;
        border-radius: 10px;
        height: 5rem;
        width: 100%;
        background: #111827;
    }
    .box-2 {
        border-radius: 10px;
        width: 100%;
        background: white;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        padding: 20px; /* Espacio interno */
    }
    .welcome{
        font-size:1.5rem;
    }
    .options {
        display: grid;
        /* Esto crea 3 columnas iguales */
        grid-template-columns: repeat(3, 1fr); 
        gap: 20px; /* Espacio entre botones */
    }
    .option-1{
        background:pink;
        border-radius:8px;
    }
    
    .option-btn {
        text-decoration: none; 
        color: #333;
        background: linear-gradient(145deg, #ffffff, #e6e6e6); /* Efecto relieve suave */
        padding: 30px 20px;
        height: 10rem;
        border-radius: 12px;
        display: flex;
        font-weight: 700;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        box-shadow: 5px 5px 10px #d9d9d9, -5px -5px 10px #ffffff;
    }
    .option-btn h1 {
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

</style>
<body>
    <x-header-bar />
    <div class="box-2">
        <div class="options">

            <a href="{{ route('productos') }}" class="option-btn" style="background:green;">
                <h1 style="color:white;">Crear Prevale</h1>
            </a>
            <a href="{{ route('clientes.index') }}" class="option-btn" style="background:#e54444">
                <h1 style="color:white;">Clientes</h1>
            </a>         
            <a href="#" class="option-btn" style="background:#305be3">
                <h1 style="color:white;">Relaciones</h1>
            </a>
            <a href="#" class="option-btn" style="background:gray">
                <h1 style="color:white;">Ajustes</h1>
            </a>
            <a href="{{ route('distribuidora.vale') }}" class="option-btn" style="background:orange">
                <h1 style="color:white;">Vales</h1>
            </a>
            <form method="POST" action="{{ route('logout') }}"w id="logout-form" style="display:none;">
                @csrf
            </form>
              <a href="#" onclick="document.getElementById('logout-form').submit();" class="option-btn" style="background:red">
                <h1 style="color:white;">Cerrar Sesion</h1>
            </a>
        </div>
    </div>
</body>
</html>