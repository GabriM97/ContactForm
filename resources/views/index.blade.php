<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Netsons Test</title>

    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body class="p-3">
    <!-- 
    <h1 class="text-3xl font-bold my-5">Test del candidato</h1>
    <p class="mb-3">Benvenuto/a nel test per la tua candidatura. </p>
    <p class="mb-3">Cosa puoi fare con questo progetto: </p>
    <ol class="ml-10 mb-3 list-disc">
        <li>aggiungere una funzionalità "form di contatto"a questa pagina. Le richieste inviate devono essere
            salvate in una model di nome Contact. Utilizza le migration per creare la tabella necessaria,
        </li>
        <li>modificare questa pagina affinché sia una landing page per un prodotto/servizio di tuo gradimento.
            L'aggiunta di effetti di movimento alla pagina è un plus gradito,</li>
        <li>aggiungere la funzionalità di autenticazione al sistema (utilizzando eventualmente librerie standard di
            laravel) per fare in modo che questa pagina sia accessibile solo a utenti registrati,
        </li>
    </ol>
    <p class="mb-3">Sei libero/a di scegliere uno o più di uno dei punti precedenti. </p>
    <p class="mb-3">Puoi anche cimentarti in qualcosa di completamente diverso,
        se lo reputi interessante. Ricorda,
        non è importante la quantità. </p>
    -->
    
    <div id="app"></div>
</body>

</html>
