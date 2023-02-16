<?php

declare(strict_types=1);

namespace App\dispatch;



use App\action\DisplayCatalogue;
use App\action\SigninAction;


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
            case 'home':
                //TODO
                break;
            case 'catalogue':
                $act = new DisplayCatalogue();
                $html = $act->execute();
                break;
            case 'panier':
                //TODO
                break;
            case 'signin':
                $act = new SigninAction();
                $html = $act->execute();
                break;
            case 'inscription':
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
    <title>CrazyCharlyDay</title>
</head>
<body>

    <header>
        <a href='?'>Accueil</a>
        <a href='?action=catalogue'>Catalogue</a>
        <a href='?'>Mon Panier</a>
        <a href='?action=signin'>Connexion</a>
        <a href='?'>Inscription</a>

    </header>
    
    $html   



</body>

<footer class='fixed bottom-0 h-10 bg-black text-center'>
        <a href=''>Mentions légales</a>
        <a href=''>Nous contacter</a>
</footer>

</html>" ;
    }


}
