Feature: Fake XML API sample
  In order for extension to work
  As an API user
  I need to be able to interact with Fake XML API

  Scenario:
    Given I am an anonymous user
    And I use "GET" request method
    When I access the resource url "/echo?status=200&Content-Type=application%2Fxml&body=%3C%3Fxml%20version%3D%221.0%22%20encoding%3D%22UTF-8%22%3F%3E%0A%3Cone%3Etwo%3C%2Fone%3E"
    And I send the request
    Then the status code should be 200
    And response should be a valid XML
    And response should validate against "one_two.xsd" schema
    And response should be identical to "one_two.xml" file

  Scenario:
    Given I use "GET" request method
    When I access the resource url "/echo?status=200&Content-Type=application%2Fxml&body=%3C%3Fxml%20version%3D%221.0%22%20encoding%3D%22UTF-8%22%3F%3E%0A%3Cone%3Etwo%3C%2Fone%3E"
    And I send the request
    Then the status code should be 200
    And response should be a valid XML
    And response header "Server" should contain "Google Frontend"
    And response should validate against "one_two.xsd" schema

  Scenario:
    Given I use "POST" request method
    When I access the resource url "/echo?status=200&Content-Type=application%2Fxml&body=%3C%3Fxml%20version%3D%221.0%22%20encoding%3D%22UTF-8%22%3F%3E%0A%3Cone%3Etwo%3C%2Fone%3E"
    And I use the "one_two.xml" file as request body
    And I set the header "Accept" to "application/vnd.arachne.v1"
    And I send the request
    Then the status code should be 200
    And response should be a valid XML
    And response should validate against "one_two.xsd" schema
    And response should be identical to "one_two.xml" file