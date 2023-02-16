<?php

namespace App\action;

use App\factory\ConnectionFactory;

class DisplayProduits extends Action
{

    public function execute(): string
    {
        $html = "";

        if (($db = ConnectionFactory::getConnection()) != null) {
            $query="SELECT nom, prix, poids, description, detail, img, lieu from produit where id = ?;";
            $req = $db->prepare($query);
            $req->execute([$_GET['id']]);
            $data = $req->fetch();
            $html .= <<<END
            <div class="produit">
                    
                   <div class='titre'  <h1>{$data['nom']}</h1></div>
                    
                   <img src="{$data['img']}" alt="{$data['nom']}">
                    
                    <div class='description'>
                    <p>{$data['description']}</p>
                    <p>{$data['detail']}</p>
                    <p>{$data['poids']}</p>
                    <p>{$data['prix']}€</p>
                    <p>{$data['lieu']}</p>
                    </div>
                 
</div>
END;
        }
        return $html;
    }

}