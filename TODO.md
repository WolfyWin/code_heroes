# Pour créer une nouvelle page sans symfony:

1. Créer une nouvelle route dans routes.php,
2. Créer la méthode du controller définie dans la route VIDE,
3. Créer et render le template twig associé,
4. Dans la méthode du controller récupérer les données de la BDD grâce au Models,
5. Créer les models si nécessaire (autant que d’entités du mcd),
6. transmettre les données récupérées au twig,
7. afficher les données transmises dans le twig,
8. mettre à jour les liens vers cette nouvelle page.

# Pour créer une nouvelle page avec symfony:

1. Créer le controleur 
2. Créer la route  #[Route] = #[attributeName(key: value)]
3. Créer la vue correspondante  dans templates