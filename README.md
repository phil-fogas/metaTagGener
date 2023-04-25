
## MetaTagGener
MetaTagGener est un outil qui vous permet d'ajouter des méta tags et des liens dans la section <head> de vos pages HTML, de créer des icônes compatibles avec tous les systèmes d'exploitation et de générer un fichier webmanifest.

## Fonctionnalités
-Vérifie si des méta et des liens sont présents et supprime les doublons éventuels\n
-Ajoute les méta et les liens qui ne sont pas présents\n
-Crée des icônes conformes aux normes des différents systèmes d'exploitation\n
-Vérifie et crée le fichier webmanifest\n

Exemples d'utilisation
Ajouter des méta et des liens dans un fichier HTML \n
```
include './scr/meta.php';
$meta = new Meta;
$meta->Fichier('index.php'); //le nom du fichier à modifier
```
Modifier un fichier HTML en temps réel
```
include './scr/meta.php';
$meta = new Meta;
$html='<!DOCTYPE html><html lang="fr" class="no-js"><head></head><body></body></html>';
echo $meta->Ecrire($html); //le html à modifier 
```
## Options disponibles
-setOption() : tableau avec les noms des systèmes d'exploitation (par exemple, ['android']) pour spécifier un système d'exploitation. \n
-setCouleur() : pour définir la couleur du contour du site (par exemple, '#000' pour noir). \n
-setApp() : pour définir le nom du site (par exemple, 'mon site'). \n
-setSite() : pour définir l'adresse du site (par exemple, 'https://votreSite.fr/'). \n

## Exigences
-PHP 7.4 ou supérieur\n
-Une image favicon.png de grande taille à la racine \n
-Un fichier index.php ou index.phtml contenant votre mise en page avec les balises <head> </head> même vides \n

## Licence

metaTagGener est distribué libre. merci de me réference. \n

## Contribuer

Si vous souhaitez contribuer à Liste de tâches, vous pouvez : \n

- Cloner le dépôt Git \n
- Créer une branche pour votre modification \n

## Contact

Si vous avez des questions ou des commentaires sur Liste de tâches, vous pouvez me contacter ici" \n
