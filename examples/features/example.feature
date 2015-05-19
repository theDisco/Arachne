Feature: Fake JSON API sample
  In order for extension to work
  As an API user
  I need to be able to interact with Fake JSON API

  Scenario:
    Given I use "GET" request method
    When I access the resource url "/key/value"
    And I send the request
    Then the status code should be 200
    And response should be a valid JSON

  Scenario:
    Given I use "GET" request method
    When I access the resource url "/one/two"
    And I send the request
    Then the status code should be 200
    And response should be a valid JSON
    And response header "Server" should contain "Google Frontend"
    And response should validate against "one_two" schema

  Scenario:
    Given I use "POST" request method
    When I access the resource url "/one/two"
    And I use the "one_two" file as request body
    And I set the header "Accept" to "application/vnd.arachne.v1"
    And I send the request
    Then the status code should be 200
    And response should be a valid JSON
    And response should be identical to "one_two" file