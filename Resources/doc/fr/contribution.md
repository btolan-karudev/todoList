#Contribution

Ce document présente la partie collaboration sur le projet ToDo &Co.  

##Première Etape : Le Fork et le Clonage:

Avant de commencer à récupérer le code source du site, il faut préparer son dossier de travail. Pour ce faire créer un dossier ou vous voulez pour pouvoir y mettre le code source. 
Dans ce dossier ouvrez le terminal `Git Bash` (souvent clique droit de la souris puis cliquer sur « Git Bash »).  
Aller sur votre navigateur, puis aller sur le lien GitHub à l’adresse suivant : https://github.com/michaelgtfr/todoList 
 
Cliquer sur le bouton `Fork`. Si vous avez plusieurs comptes, choisissez celui sur lequel il doit être mis. Le code est copié sur votre compte GitHub.  

Cliquer sur le bouton `Code` puis copier-coller le lien sous l’onglet « Clone with HTTPS ». Aller sur le terminal et cloné le code sur votre machine en écrivant « `git clone ………… est le lien donnée ……` »  

**exemple :** `git clone https://github.com/michaelgtfr/todoList.git`  

_Le téléchargement commence. A Noter : 
Pendant la phase de téléchargement du code source, il se peut que Git demande votre login et votre mot de passe._ 

##Commit :  
Dans un premier temps. Vous allez créer une nouvelle branche. Cette branche aura comme nom, le nom de la fonctionnalité créer. Les fonctionnalités à créer son écrite dans la partie issues sur le GitHub de l’auteur du projet. Pour créer cette branche vous pouvez utiliser la commande `git checkout –b …….(le nom de la branche)…` .  
**ex:**	 `git checkout –b tests_unitaire_update`  

_Après cette étape vous pouvez faire vos modifications._  

Votre modification faîte, vous l’ajouter à l’index avec la commande `git add ….((le nom du fichier à ajouter))…` .   
**ex:**	 `git add Security.php`  

Puis vous l’enregistrez avec la commande  `git commit –m "le texte expliquant la modification sur le fichier"` . Cette étape commit la modification.  
**ex:** 	`git commit -m "add the securityCheck method"`

##Les « Pull Request » :  

Avant d’envoyer vos modifications sur GitHub. Faite un `git status` pour voir si toute vos modifications son bien enregistrer sur Git.
Pour envoyer le code sur votre compte GitHub, vous allez écrire `git push origin ….(le nom de votre branche)…`.  
**ex:**	 `git push origin tests_unitaire_update`
  
_Celle-ci va alors envoyer sur GitHub le code sous le nom de votre branche._

De votre compte GitHub sur le navigateur de votre choix, dans le repository « Todolist » vous avez un élément apparaissant sous le nom de votre branche. Cliquer sur `Compare & pull request`, celle-ci permet d’envoyer sur le GitHub de l’auteur principal votre modification. Un champ de texte apparaît avec plusieurs autres éléments permettant entre autre de voir les modifications, les commits,… . Ecrivez dans le champ texte la fonctionnalité créée, le détail des modifications et les issues résolues. Cliquer sur `Create pull request` .  

_Une fois votre pull request envoyée, l'auteur du projet consultera votre proposition, et vous recevrez une notification par GitHub lorsqu'il les aura intégrées ou refusées._