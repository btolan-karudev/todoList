Feature: user
  In order to connect
  As website user
  I must validate put write in the form

  Scenario: this connect
    Given I want to connect to the name of "username_test"
    Given I am on "/login"
    When I fill in "username" with "username_test"
    And I fill in "password" with "admin"
    And I press "button"
    Then I should see "Créer une nouvelle tâche"

  Scenario: create a user
    Given I am logged in as an admin
    When I click "Créer un utilisateur"
    And I fill in "Nom d'utilisateur" with "test_username"
    And I fill in "Mot de passe" with "1"
    And I fill in "Tapez le mot de passe à nouveau" with "1"
    And I fill in "Adresse email" with "emailtest@gmail.com"
    And I press "Ajouter"
    Then I should see "L'utilisateur a bien été ajouté."

  Scenario: update a user
    Given I am logged in as an admin
    And there is an exiting user on behalf of "username_two"
    When I click "Liste des utilisateurs"
    And want to modify account "username_Two"
    And I fill in "Mot de passe" with "1"
    And I fill in "Tapez le mot de passe à nouveau" with "1"
    And I press "Modifier"
    Then I should see "L'utilisateur a bien été modifié"