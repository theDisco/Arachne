Feature: twitter sample
  In order for extension to work
  As an API user
  I need to be able to retrieve twitter sample

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
    And I use the "body" file as request body
    And I send the request
    Then the status code should be 200
    And response should be a valid JSON
    And response should be identical to "one_two" file