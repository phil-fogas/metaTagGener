
## MetaTagGener
MetaTagGener est un outil qui vous permet d'ajouter des méta tags et des liens dans la section <head> de vos pages HTML, de créer des icônes compatibles avec tous les systèmes d'exploitation et de générer un fichier webmanifest.

## Fonctionnalités
- Vérifie si des méta et des liens sont présents et supprime les doublons éventuels<br>
- Ajoute les méta et les liens qui ne sont pas présents<br>
- Crée des icônes conformes aux normes des différents systèmes d'exploitation<br>
- Vérifie et crée le fichier webmanifest\n

Exemples d'utilisation
Ajouter des méta et des liens dans un fichier HTML <br>
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
- setOption() : tableau avec les noms des systèmes d'exploitation (par exemple, ['android']) pour spécifier un système d'exploitation. <br>
- setCouleur() : pour définir la couleur du contour du site (par exemple, '#000' pour noir). <br>
- setApp() : pour définir le nom du site (par exemple, 'mon site').<br>
- setSite() : pour définir l'adresse du site (par exemple, 'https://votreSite.fr/'). <br>

## Exigences
- PHP 7.4 ou supérieur\n
- Une image favicon.png de grande taille à la racine <br>
- Un fichier index.php ou index.phtml contenant votre mise en page avec les balises <head> </head> même vides <br>

## Licence

metaTagGener est distribué libre. merci de me réference. <br>

## Contribuer

Si vous souhaitez contribuer à Liste de tâches, vous pouvez : <br>

- Cloner le dépôt Git \n
- Créer une branche pour votre modification <br>

## Contact

Si vous avez des questions ou des commentaires sur Liste de tâches, vous pouvez me contacter ici" <br>
