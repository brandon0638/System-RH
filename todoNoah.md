## L’application suggère les régimes et l’activité sportive nécessaire pendant une durée
- Tout commence par le login :
    - Si c'est un login admin, rediriger vers le dashboard
    - Sinon c'est un login utilisateur normal
    - lors du login , il verifie si l'imc de l'utilisateur est null, si c'est le cas il faut le remplir :
        l'imc se calcule par le poids en kg / la taille en m^2, mais les donnees dans la db sont poids en kg et taille
        en cm, donc il faut encore convertir la taille en m
    - Si l'utilisateur veut voir le dashboard, il doit se connecter en tant qu'admin
    - Apres login, si l'id de l'utilisateur doit etre stocke dans une variable session (pas encore le cas dans la fonction post login())
    - Pour l'instant : l'admin se fait rediriger vers le dashboard
    - Maintenant : il faut que si la connection est de type user ($user['role'] === 'user'), il se fait rediriger vers
    le hero page
    - Dans le hero page , il y aura une section qui dira 'Diet Balance, votre solution pour vos objectifs de poids ',
    en dessous de cette section se trouve trois bouttons avec les labels suivants : Perdre du poids, Gagner du Poids,
    atteindre son IMC , chacun pointant vers un lien correspondant : /regime/predre ; /regime/gagner; /regime/imc
    - Ces trois bouttons respectifs sont plus ou moins grand afin de mettre de l'emphase sur les choix que peuvent faire l'utilisateur (et sera plus facile a voir)
    - Cliquer sur ces liens va rediriger dans une meme page , dont les resultats qui s'affichent changent dependant du choix 
    - Deja qu'import le choix , la page affichera en premier une section bilan de l'utilisateur connecte, ce bilan affichera son nom , son genre , son poids , sa taille et som IMC
    - Ensuite voila comment le contenur va varier : si on a choisi /regime/perdre , il affichera les regimes qui permettent de perdre du poids (syntaxe sql a respecter), si on a choisi /regime/gagner, afficher les regimes qui
    permettent de gagner du poids (pour savoir si un regime fait gagner ou perdre du poids, sa variation_poids_grammes sera positif si gain et negatif si perte de poids), si on a choisi l'option d'atteindre son IMC ideal (idealement entre 18,5 bet 24,9)
    - Afficher les sports adequats pour chaque lien (meme regle que pour les precedents pour perdre et gagner)
    - Note to self : doit respecter structure mvc
    - Dans /regime/perdre et dans /regime/gagner ajouter un moyen d'exporter le pdf suivant :
    chaque page aure un input nombre : variation (perte ou gain) poids desire (en kg). avec un texte : choisissez votre programme maintenant
    par exemple mon input sera 3 kg , il importera un pdf dans lequel il affichera les sport et la duree necessaire
    correspondante pour chaque sport pour (perdre ou gagner du poids , selon l'option de l'user) atteindre la variation de poids desire; de meme pour le regime (sachant que le regime doit etre soit gain soit perte de poids selon le choix de)
    
## On peut rajouter de l’argent dans son porte monnaie en rentrant un code

- Init db [ok]
- Creation donnees de test [ok]
    - 1 user
    - 1 code avec valeur > 0

- Page pour redeem un code (dans la meme page que hero, un bouton dans une differente section menant avec un lien vers redeem code):
    - Text input du code lui meme
    - Upon Submit :
        - Verifier si code existe
        - Si code existe , augmenter balance de l'utilisateur actuel (findUserById ( session user id))
        - Marquer le code comme utilise (utilise = 1)
        - Sinon message erreur
    
Tables inclues : User (balance), Code

## L’utilisateur peut avoir une option Gold qu’il va payer en une  seule fois, à vous de proposer le prix et le mode d’accès

- Page d'achat d'offres (table Options, page situe dans la page hero lors de la connexion User)
- Donnes pour l'instant : Option Gold (donnes a creer [deja cree ])
- Faire le choix d'Option (afficher les choix d'options possible )
- Apres achat : ajout dans OptionUser, diminuer la balance de l'user par la valeur de l'option
- Appliquer la reduction (a discuter)