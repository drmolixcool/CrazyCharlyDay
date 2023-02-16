<?php

declare(strict_types=1);

namespace App\dispatch;



use App\action\DisplayCatalogue;
use App\action\DisplayProduits;

class Dispatcher
{
    private ?string $action = null;

    public function __construct(string $action)
    {
        $this->action = $action;
    }


    public function run(): void
    {
        $html = '';
        switch($this->action) {
            case 'produit':
                $act = new DisplayProduits();
                $html = $act->execute();
                break;
            case 'catalogue':
                $act = new DisplayCatalogue();
                $html = $act->execute();
                break;
            case 'panier':
                //TODO
                break;
            case 'compte':
                //TODO
                break;
            default:
                break;
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html): void
    {
        echo
        "<!DOCTYPE html>
<html lang='fr'>
<head>
    <link rel='stylesheet' href='style.css' >
    <meta charset='UTF-8'>
    <link rel='stylesheet' href='https://unpkg.com/leaflet@1.3.1/dist/leaflet.css' integrity='sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==' crossorigin='' />
    <title>CrazyCharlyDay</title>
</head>
<body>

    <header>
        <a href='?'>Accueil</a>
        <a href='?action=catalogue'>Catalogue</a>
        <a href='?'>Mon Panier</a>
        <a href='?'>Mon Compte</a>

    </header>
    
    $html   

<img src='images/1.jpg' alt='image1'>


</body>

<footer class='fixed bottom-0 h-10 bg-black text-center'>
        <a href=''>Mentions légales</a>
        <a href=''>Nous contacter</a>
</footer>

</html>" ;
    }


}
