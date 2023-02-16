<?php

declare(strict_types=1);

namespace App\dispatch;



class Dispatcher
{
    private ?string $action = null;

    public function __construct(string $action)
    {
        $this->action = $action;
    }


    public function run(): void
    {


    }

    private function renderPage(string $html): void
    {
        echo
        "<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <title>CrazyCharlyDay</title>
</head>
<body>

    <header>
        <a href=''>Accueil</a>
        <a href=''>Catalogue</a>
        <a href=''>Mon Panier</a>
        <a href=''>Mon Compte</a>

    </header>

<img src='Images/1.jpg' alt='image1'>


</body>

<footer class='fixed bottom-0 h-10 bg-black text-center'>
        <a href=''>Mentions légales</a>
        <a href=''>Nous contacter</a>
</footer>

</html>" ;
    }


}
