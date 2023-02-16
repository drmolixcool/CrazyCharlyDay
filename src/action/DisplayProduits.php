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
                    <h1>{$data['nom']}</h1>
                    <img src="{$data['img']}" alt="{$data['nom']}">
                    <p>{$data['description']}</p>
                    <p>{$data['detail']}</p>
                    <p>{$data['poids']}</p>
                    <p>{$data['prix']}€</p>
                    <p>{$data['lieu']}</p>
                    <div id="map"></div>
                    
                   
                    <script type="text/javascript">
           
            var lat = 48.852969;
            var lon = 2.349903;
            var macarte = null;
           
            function initMap() {
           
                macarte = L.map('map').setView([lat, lon], 11);
           
                L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
           
                    attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                    minZoom: 1,
                    maxZoom: 20
                }).addTo(macarte);
            }
            window.onload = function(){
		initMap(); 
            };
        </script>
                    
</div>
END;
        }
        return $html;
    }

}