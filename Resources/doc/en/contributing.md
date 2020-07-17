#Contributing

This document presents the collaboration part on the ToDo & Co project.

##First step: Fork and Cloning:

Before starting to recover the source code of the site, it is necessary to prepare its working file. To do this create a folder where you want to be able to put the source code there.
In this folder open the `Git Bash` terminal (often right click the mouse then click on" Git Bash ").  
Go to your browser, then go to the GitHub link at the following address: https://github.com/michaelgtfr/todoList

Click on the `Fork` button. If you have several accounts, choose the one on which it should be put. The code is copied to your GitHub account.

Click on the `Code` button then copy and paste the link under the" Clone with HTTPS "tab. Go to the terminal and clone the code on your machine by writing “` git clone ………… is the given link …… `”

**example:** `git clone https://github.com/michaelgtfr/todoList.git`

_The download begins. To note :
During the download phase of the source code, Git may ask for your login and password._

## Commit:

Firstly. You will create a new branch. This branch will have as name, the name of the functionality to create. The features to create sound written in the section on the GitHub of the author of the project. To create this branch you can use the command `git checkout –b ……. (The name of the branch)…`.  
**ex:** `git checkout –b tests_unitaire_update`

_After this step you can make your changes._

Once your modification is done, you add it to the index with the command `git add .... ((the name of the file to add)) ...`.  
**ex:** `git add Security.php`  

Then you save it with the command `git commit –m" the text explaining the modification on the file "`. This step committed the change.  
**ex:** `git commit -m" add the securityCheck method "`

##Les « Pull Request » :

Before submitting your changes to GitHub. Do a `git status` to see if all your changes are saved to Git.
To send the code to your GitHub account, you will write `git push origin .... (the name of your branch) ...`.  
**ex:** `git push origin tests_unitaire_update`

_This will then send the code under GitHub under the name of your branch._

From your GitHub account on the browser of your choice, in the "Todolist" repository you have an item appearing under the name of your branch. Click on `Compare & pull request`, this will send your modification to the GitHub of the main author. A text field appears with several other elements allowing, among other things, to see modifications, commits, etc. Write in the text field the functionality created, the details of the modifications and the resolved issues. Click on `Create pull request`.

_Once your pull request has been sent, the author of the project will consult your proposal, and you will receive a notification by GitHub when it has integrated or refused it._