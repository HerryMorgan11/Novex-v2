<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/auth/login.css')
</head>
<body>
    <main class="container-main">
        <img src="{{ asset('assets/background/fondo-login.jpg') }}" alt="Background" class="background-image">
        <form action="" method="POST" class="form-container">
            <div class="email">
                <label>Email</label>
                <input type="email" class="input-class">
            </div>
            
            <div class="password">
                <label>Contraseña</label>
                <input type="password" class="input-class">
            </div>
        <div class="boton-container">
            <button type="submit" class="boton-login">Entrar</button>
        </div>
       
        </form>
    </main>

</body>
</html>