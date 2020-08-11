Feature: task
  In order use the task service
  as website user
  I need to use create, modify or delete tasks

  Scenario: create a task
    Given I am logged in as an admin
    When I click "Créer une nouvelle tâche"
    And I fill in "Title" with "title Testing"
    And I fill in "Content" with "content Testing"
    And I press "Ajouter"
    Then I should see "La tâche a été bien été ajoutée."

  Scenario: update a task
    Given I am logged in as an admin
    And there existing task on behalf of "test task"
    When I click "Consulter la liste des tâches à faire"
    And I click "test task"
    And I press "Modifier"
    Then I should see "La tâche a bien été modifiée."

  Scenario: delete a task
    Given I am logged in as an admin
    And there existing task on behalf of "test task"
    When I click "Consulter la liste des tâches à faire"
    And want delete the task "test task"
    Then I should see "La tâche a bien été supprimée."

  Scenario: toggle a task
    Given I am logged in as an admin
    And there existing task on behalf of "test task"
    When I click "Consulter la liste des tâches à faire"
    And I want toggle the task "test task"
    Then I should see "La tâche test task a bien été marquée comme faite."