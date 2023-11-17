<?php
// Création de la classe Utilitaires avec création de fonction qui permet de générer un nombre aléatoire entre un nombre minimum et un nombre maximum pris en paramètres (min et max).
class Utilitaires {
    public static function générerNombreAléatoire($min, $max) {
        return rand($min, $max);
    }
}
// Création de la classe Joueur
class Joueur {
    public $nom;
    public $billes;
    public $malus;
    public $bonus;
    public $criGuerre;
    // Constructeur de la classe Joueur qui permet de créer un joueur avec un nom, un nombre de billes, un malus, un bonus et un cri de guerre.
    public function __construct($nom, $billes, $malus, $bonus, $criGuerre) {
        $this->nom = $nom;
        $this->billes = $billes;
        $this->malus = $malus;
        $this->bonus = $bonus;
        $this->criGuerre = $criGuerre;
    }
    // Fonction pour gagner des billes en fonction du gain du joueur et du nombre de billes de l'adversaire.
    public function gagner($adversaire) {
        $this->billes = $this->billes + $this->bonus + $adversaire->billes;
    }
    // Fonction pour perdre des billes en fonction de la perte du joueur et du nombre de billes de l'adversaire.
    public function perdre($adversaire) {
        $this->billes = $this->billes - $this->malus - $adversaire->billes ;
    }
}
// Création de la classe Adversaire
class Adversaire {
    public $nom;
    public $billes;
    public $âge;
    // Constructeur de la classe Adversaire qui permet de créer un adversaire avec un nom, un nombre de billes et un âge.
    public function __construct($nom, $billes, $âge) {
        $this->nom = $nom;
        $this->billes = $billes;
        $this->âge = $âge;
    }
}

class Jeu {
    private $joueur;
    private $adversaires;

    public function __construct() {
        // Initialisation des joueurs et des adversaires

        // Début du jeu
        $this->démarrerJeu();
    }

    public function démarrerJeu() {
        // Sélection aléatoire du joueur
        $this->joueur = $this->getJoueurAléatoire();

        // Sélection aléatoire du niveau de difficulté
        $difficulté = Utilitaires::générerNombreAléatoire(1, 3);

        // Initialisation des adversaires en fonction du niveau de difficulté
        $this->initAdversaires($difficulté);

        // Début des rencontres
        $this->démarrerRencontres();
    }
    //fonction pour sélectionner un joueur aléatoire parmis les 3 joueurs proposés dans le tableau.
    private function getJoueurAléatoire() {
        $joueurs = [
            new Joueur("Seong Gi-hun", 15, 2, 1, "gg... "),
            new Joueur("Kang Sae-byeok", 25, 1, 2, "ez pez "),
            new Joueur("Cho Sang-woo", 35, 0, 3, "je suis le meilleur! ")
        ];
        // Génère un nombre aléatoire entre 0 et 2 afin de sélectionner un index (donc joueur) aléatoire dans le tableau.
        $indiceAléatoire = Utilitaires::générerNombreAléatoire(0, count($joueurs) - 1);
        $joueurSélectionné = $joueurs[$indiceAléatoire];
        echo "Le joueur sélectionné est " . $joueurSélectionné->nom ;
        return $joueurs[$indiceAléatoire];
    }

    private function initAdversaires($difficulté) {
        // Initialisation des adversaires en fonction du niveau de difficulté
        // si la difficulté est 1 alors il y aura 5 adversaires, si 2 alors 10 et si 3 alors 20
        $nombreAdversaires = ($difficulté == 1) ? 5 : (($difficulté == 2) ? 10 : 20);
        if ($difficulté == 1){
            echo"<br>la difficulté est faible, la chance vous sourit ! 5 manches à jouer<br>";
        }
        else if ($difficulté == 2){
            echo"<br>la difficulté est désormais moyenne. 10 manches à jouer<br>";
        }
        else{
            echo"<br>Difficulté Hardcore, bonne chance XD ! 20 manches à jouer<br>";
        }
        // Boucle qui permet de créer les adversaires en fonction du nombre de manches décidé si dessus en fonction de la difficulté.
        for ($i = 0; $i < $nombreAdversaires; $i++) {
            $adversaire = new Adversaire(
                // Génère un nom aléatoire pour l'adversaire en fonction de son numéro d'adversaire (i), un nombre de billes aléatoire entre 1 et 20 et un âge aléatoire entre 1 et 100.
                "Adversaire " . ($i + 1),
                Utilitaires::générerNombreAléatoire(1, 20),
                Utilitaires::générerNombreAléatoire(1, 100)
            );

            $this->adversaires[] = $adversaire;
        }
    }
    //fonction pour démarrer des rencontres entre le joueur et les adversaires
    private function démarrerRencontres() {
        // Boucle qui permet de lister les adversaire un a un et qui permet de jouer contre eux.
        foreach ($this->adversaires as $adversaire) {
            echo "Billes actuelles : " . $this->joueur->billes . "<br>";
            echo "Billes de l'adversaire : " . $adversaire->billes . "<br>";
    
            // Bonus : tu peux tricher si l'adversaire a plus de 70 ans (ce n'est pas cool, mais tu peux)
            $choixTriche = Utilitaires::générerNombreAléatoire(0, 1);
            if ($adversaire->âge > 70 && $choixTriche == 1) {
                echo "Je triche, MALVEILLANCE MAX ! ";
                $this->joueur->billes += $adversaire->billes + $this->joueur->bonus;
                echo "J'ai gagné " . $adversaire->billes + $this->joueur->bonus . " billes ! <br>";
            } else {
                // Choix du joueur pair ou impaire aléatoire
                $devineJoueur = Utilitaires::générerNombreAléatoire(0, 1);
    
                // Vérifie si le joueur a deviné correctement
                // bien deviné :
                if ($devineJoueur == 0 && $adversaire->billes % 2 == 0 || $devineJoueur == 1 && $adversaire->billes % 2 != 0) {
                    echo "J'ai eu le bon guess nickel! ";
                    $this->joueur->gagner($adversaire);
                    echo "Vous avez gagné " . $this->joueur->bonus + $adversaire->billes . " billes ! <br>" ;
                    echo $this->joueur->nom . " : " . $this->joueur->criGuerre . " <br>";
                } //pas deviné :
                else {
                    echo "NOONNNN j'ai eu le mauvais guess! ";
                    $this->joueur->perdre($adversaire);
                    echo "Vous avez perdu " . $this->joueur->malus + $adversaire->billes . " billes ! <br>" ;
                }
            }
    
            // Vérifie si le joueur a au moins 1 bille restante, si non il meurt
            if ($this->joueur->billes <= 0) {
                echo "Vous n'avez plus de billes, adieu. ";
                break;
            }
        }
        // si oui il gagne.
        if ($this->joueur->billes > 0) {
            echo "Félicitations ! Vous avez terminé le jeu et remporté 45,6 milliards de Won Sud-Coréens ! ";
        }
    }
}

// Démarrage du jeu
new Jeu();