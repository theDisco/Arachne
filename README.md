[![Build Status](https://travis-ci.org/theDisco/Arachne.svg)](https://travis-ci.org/theDisco/Arachne)

Arachne
=======

Arachne is a Behat 3 extension for testing web services. Similarly to Mink, it exposes multiple methods in
a context to facilitate testing of RESTful APIs.

Installation
============

The preferred way of installing Arachne is through composer. Just add Arachne as a dependency to your project
and you are good to go.

```json
{
    "require-dev": {
        "aferalabs/arachne": "0.1.*"
    }
}
```

Configuration
=============

An example configuration was set up in the example project. Take a look at `examples/behat.yml`.

```yml
default:
  extensions:
    Arachne\ServiceContainer\ArachneExtension:
      base_url: http://echo.jsontest.com
      paths:
        schema_file_dir: %paths.base%/schemas
        request_file_dir: %paths.base%/requests
        response_file_dir: %paths.base%/responses
      auth:
        provider: Arachne\Auth\DummyProvider
      headers:
        Authorization: Token token=123456
  suites:
    json:
      contexts:
        - Arachne\Context\ArachneContext
          - headers:
              X-Example-Header: Example Value
```

In order to enable the extension, you need to add it to the extensions node of your config. 

**base_url**

`base_url` is the only required configuration value and will be prepended to all requests made by the extension.
 
**paths.schema_file_dir**

Under the hood Arachne uses [json schema](http://json-schema.org/) validator or 
[xml schema](https://github.com/seromenho/XmlValidator/) validator to validate the structure
of the response. In order to use `response should validate against "one_two[.json|.xsd]" schema`, 
extensions needs to know, where to find schema files. `paths.schema_file_dir` tells Arachne, 
which folder are the schema files located in. In this particular example, Arachne will look for a schema in 
`examples/schemas/one_two.json` file. 

```yml
  Scenario:
    Given I use "GET" request method
    When I access the resource url "/one/two"
    And I send the request
    Then the status code should be 200
    And response should be a valid JSON
    And response header "Server" should contain "Google Frontend"
    And response should validate against "one_two" schema # <---
```

Hint: It is also possible to validate a xml response against a xsd file. You just need to specify the file type 
of the schema file explicitly (if you don't enter a file type, json is used as file type):

```yml
Scenario:
    Given I use "GET" request method
    When I access the resource url "/one/two"
    And I send the request
    Then the status code should be 200
    And response should be a valid XML
    And response header "Server" should contain "Google Frontend"
    And response should validate against "one_two.xsd" schema # <---
```

**paths.request_file_dir**

Sometimes requests are relatively large and their content would make the features unreadable. Therefore
Arachne supports setting request bodies using content of a file. `paths.request_file_dir` tells Arachne,
which folder are the request files located in. In this case, Arachne will look for request body in
`examples/requests/one_two[.json|.xml]` file.
 
```yml
  Scenario:
    Given I use "POST" request method
    When I access the resource url "/one/two"
    And I use the "one_two" file as request body # <---
    And I send the request
    Then the status code should be 200
    And response should be a valid JSON
    And response should be identical to "one_two" file
```

Hint: It is also possible to use a xml file as request body. You just need to specify the file type 
of the xml file explicitly (if you don't enter a file type, json is used as file type):

```yml
  Scenario:
    Given I use "POST" request method
    When I access the resource url "/one/two"
    And I use the "one_two.xml" file as request body # <---
    And I send the request
    Then the status code should be 200
    And response should be a valid XML
    And response should be identical to "one_two.xml" file
```

**paths.response_file_dir**

Similarly as in case of request files, responses delivered by the webservice might be relatively large.
In order to validate not only the schema of the response, but also it's content, Arachne supports comparing
the content of the response body with content of a file. `paths.response_file_dir` tells Arachne,
which folder are the response files located in. In this case, Arachne will look for a response body in
`examples/responses/one_two[.json|.xml]` file.

```yml
  Scenario:
    Given I use "POST" request method
    When I access the resource url "/one/two"
    And I use the "one_two" file as request body
    And I send the request
    Then the status code should be 200
    And response should be a valid JSON
    And response should be identical to "one_two" file # <---
```

Hint: It is also possible to validate the content of a xml response. You just need to specify the file type 
of the xml file explicitly (if you don't enter a file type, json is used as file type):

```yml
  Scenario:
    Given I use "POST" request method
    When I access the resource url "/one/two"
    And I use the "one_two.xml" file as request body
    And I send the request
    Then the status code should be 200
    And response should be a valid XML
    And response should be identical to "one_two.xml" file # <---
```

**auth.priovider**

Some of the web services require some kind of persistance between the requests. In order to do that you can
use an authentication provider to perform authentication before the test starts and provide its result to the
context. Use this config variable, to tell Arachne to use authentication provider before the test will start.
You can read more about authentication providers below.

**headers**

Not all web services are open and many of them require authorization. Web services also use versioning of the
resources. That's where headers come in play. This configuration allows you to set headers that will be send with
each request. This configuration variable supports any amount of headers you want to be send with each request.
To understand how the headers are set, read the headers section below.

Steps
=====

Given/When
----------

**I am an anonymous user**
 
If Arachne was set up to use Auth Provider, you can force the current scenario _not_ to pass the current client to the
`prepare` method of Auth Provider and therefore omit the authentication. This is useful if you are testing the error responses
for not registered users or perform authentication.

**I use ".*" request method**

Sets the request method to the provided http verb.

**I access the resource url ".*"**

Sets the path for the request.

**I use the ".*" file as request body**

Uses the content of the file as a request body.

**I set the header ".*" to ".*"**

Sets a header to a provided value. Read more about headers below to understand the dependencies.

**I send the request**

Has to be explicitly called to send the request.
 
Then
----

**the status code should be \d+**

Validates, if the returned status code is equal to the expected value.

**response should be a valid JSON**

Validates, if the response body can be deserialized as a valid JSON.

**response should be a valid XML**

Validates, if the response body can be deserialized as a valid XML.

**response header ".*" should contain ".*"**

Validates, if the content of a header is equal the expected value.

**response should validate against ".*" schema**

Validates, if the returned response validates again schema.
  
**response should be identical to ".*" file**

Validates, if the response body is equal to the content of the file. It is assumed that response is a JSON string.

Auth Provider
=============

In order to perform authentication, you can create an authentication provider and let Arachne authenticate the client,
before the test will start. Each provider has to extend `Arachne\Auth\BaseProvider` and provide the logic 
of authentication. Before each request Arachne will execute `prepare` method, which you can use to alter the client
and e.g. add authentication headers. A simple authentication provider could look like the class below.

```php
use Arachne\Auth\BaseProvider;

class LoginProvider extends BaseProvider
{
    private $sessionToken;

    public function authenticate()
    {
        $client = $this->getClient();
        $client->setPath('/login');
        $client->setRequestBody(json_encode(array('u' => 'user', 'p' => 'pa$$')));
        $response = $client->send();

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $body = json_decode($response->getBody());
        $this->sessionToken = $body->sessionToken;

        return true;
    }

    public function prepare(ClientInterface $client)
    {
        $client->addHeader('X-Session-Token', $this->sessionToken);
    }
}
```

Headers
=======

There are multiple ways of setting headers. In the first step headers will be set during initialization of the
context and passed to the constructor of the context. You can pass the headers through the context configuration in
the suite.

```yml
default:
  suites:
    json:
      contexts:
        - Arachne\Context\ArachneContext:
          - headers:
              X-Example-Header: Example Value
```

If you use custom context, make sure to call the constructor of `ArachneContext`.

```php
use Arachne\Context\ArachneContext

class MyContext extends ArachneContext
{
    public function __construct(array $params)
    {
        // ... process some custom params
        parent::__construct($params);
    }
}
```

You can also set up header in the extension configuration.

```yml
default:
  extensions:
    Arachne\ServiceContainer\ArachneExtension:
      headers:
        Authorization: Token token=123456
```

Any header set in the extension configuration will overwrite the header provided during context initialization.
This is a good place to set your authorization or accept headers, because they will be passed to each request.

Headers can also be passed directly in the feature. Any headers provided in the feature will overwrite the headers
set before in extension config or initilization param.

```yml
# ...
    And I set the header "Accept" to "application/vnd.arachne.v1"
# ...
```

How To Run Example
==================

In order to run the examples provided in the repository, follow below steps.

```bash
git clone git@github.com:theDisco/Arachne.git
cd Arachne
composer install
```

JSON examples:

```bash
vendor/bin/behat -c examples/json/behat.yml
```

The output should be similar to the one below.

```
Feature: Fake JSON API sample
  In order for extension to work
  As an API user
  I need to be able to interact with Fake JSON API

  Scenario:                                     # features/example.feature:6
    Given I am an anonymous user                # Arachne\Context\ArachneContext::iAmAnAnonymousUser()
    And I use "GET" request method              # Arachne\Context\ArachneContext::iUseRequestMethod()
    When I access the resource url "/key/value" # Arachne\Context\ArachneContext::iAccessTheResourceUrl()
    And I send the request                      # Arachne\Context\ArachneContext::iSendTheRequest()
    Then the status code should be 200          # Arachne\Context\ArachneContext::theStatusCodeShouldBe()
    And response should be a valid JSON         # Arachne\Context\ArachneContext::responseShouldBeAValidJson()

  Scenario:                                                       # features/example.feature:14
    Given I use "GET" request method                              # Arachne\Context\ArachneContext::iUseRequestMethod()
    When I access the resource url "/one/two"                     # Arachne\Context\ArachneContext::iAccessTheResourceUrl()
    And I send the request                                        # Arachne\Context\ArachneContext::iSendTheRequest()
    Then the status code should be 200                            # Arachne\Context\ArachneContext::theStatusCodeShouldBe()
    And response should be a valid JSON                           # Arachne\Context\ArachneContext::responseShouldBeAValidJson()
    And response header "Server" should contain "Google Frontend" # Arachne\Context\ArachneContext::responseHeaderShouldContain()
    And response should validate against "one_two.json" schema    # Arachne\Context\ArachneContext::responseShouldValidateAgainstSchema()

  Scenario:                                                       # features/example.feature:23
    Given I use "POST" request method                             # Arachne\Context\ArachneContext::iUseRequestMethod()
    When I access the resource url "/one/two"                     # Arachne\Context\ArachneContext::iAccessTheResourceUrl()
    And I use the "one_two.json" file as request body             # Arachne\Context\ArachneContext::iUseTheFileAsRequestBody()
    And I set the header "Accept" to "application/vnd.arachne.v1" # Arachne\Context\ArachneContext::iSetTheHeaderTo()
    And I send the request                                        # Arachne\Context\ArachneContext::iSendTheRequest()
    Then the status code should be 200                            # Arachne\Context\ArachneContext::theStatusCodeShouldBe()
    And response should be a valid JSON                           # Arachne\Context\ArachneContext::responseShouldBeAValidJson()
    And response should be identical to "one_two.json" file       # Arachne\Context\ArachneContext::responseShouldBeIdenticalToFile()

  Scenario:                                                       # features/example.feature:33
    Given I use "POST" request method                             # Arachne\Context\ArachneContext::iUseRequestMethod()
    When I access the resource url "/one/two"                     # Arachne\Context\ArachneContext::iAccessTheResourceUrl()
    And I use the "one_two" file as request body                  # Arachne\Context\ArachneContext::iUseTheFileAsRequestBody()
    And I set the header "Accept" to "application/vnd.arachne.v1" # Arachne\Context\ArachneContext::iSetTheHeaderTo()
    And I send the request                                        # Arachne\Context\ArachneContext::iSendTheRequest()
    Then the status code should be 200                            # Arachne\Context\ArachneContext::theStatusCodeShouldBe()
    And response should be a valid JSON                           # Arachne\Context\ArachneContext::responseShouldBeAValidJson()
    And response should validate against "one_two" schema         # Arachne\Context\ArachneContext::responseShouldValidateAgainstSchema()
    And response should be identical to "one_two" file            # Arachne\Context\ArachneContext::responseShouldBeIdenticalToFile()

4 scenarios (4 passed)
30 steps (30 passed)
0m1.05s (10.29Mb)
```

JSON examples:

```bash
vendor/bin/behat -c examples/xml/behat.yml
```

The output should be similar to the one below.

```
Feature: Fake XML API sample
  In order for extension to work
  As an API user
  I need to be able to interact with Fake XML API

  Scenario:                                                                                                                                                                         # features/example.feature:6
    Given I am an anonymous user                                                                                                                                                    # Arachne\Context\ArachneContext::iAmAnAnonymousUser()
    And I use "GET" request method                                                                                                                                                  # Arachne\Context\ArachneContext::iUseRequestMethod()
    When I access the resource url "/echo?status=200&Content-Type=application%2Fxml&body=%3C%3Fxml%20version%3D%221.0%22%20encoding%3D%22UTF-8%22%3F%3E%0A%3Cone%3Etwo%3C%2Fone%3E" # Arachne\Context\ArachneContext::iAccessTheResourceUrl()
    And I send the request                                                                                                                                                          # Arachne\Context\ArachneContext::iSendTheRequest()
    Then the status code should be 200                                                                                                                                              # Arachne\Context\ArachneContext::theStatusCodeShouldBe()
    And response should be a valid XML                                                                                                                                              # Arachne\Context\ArachneContext::responseShouldBeAValidXml()
    And response should validate against "one_two.xsd" schema                                                                                                                       # Arachne\Context\ArachneContext::responseShouldValidateAgainstSchema()
    And response should be identical to "one_two.xml" file                                                                                                                          # Arachne\Context\ArachneContext::responseShouldBeIdenticalToFile()

  Scenario:                                                                                                                                                                         # features/example.feature:16
    Given I use "GET" request method                                                                                                                                                # Arachne\Context\ArachneContext::iUseRequestMethod()
    When I access the resource url "/echo?status=200&Content-Type=application%2Fxml&body=%3C%3Fxml%20version%3D%221.0%22%20encoding%3D%22UTF-8%22%3F%3E%0A%3Cone%3Etwo%3C%2Fone%3E" # Arachne\Context\ArachneContext::iAccessTheResourceUrl()
    And I send the request                                                                                                                                                          # Arachne\Context\ArachneContext::iSendTheRequest()
    Then the status code should be 200                                                                                                                                              # Arachne\Context\ArachneContext::theStatusCodeShouldBe()
    And response should be a valid XML                                                                                                                                              # Arachne\Context\ArachneContext::responseShouldBeAValidXml()
    And response header "Server" should contain "Google Frontend"                                                                                                                   # Arachne\Context\ArachneContext::responseHeaderShouldContain()
    And response should validate against "one_two.xsd" schema                                                                                                                       # Arachne\Context\ArachneContext::responseShouldValidateAgainstSchema()

  Scenario:                                                                                                                                                                         # features/example.feature:25
    Given I use "POST" request method                                                                                                                                               # Arachne\Context\ArachneContext::iUseRequestMethod()
    When I access the resource url "/echo?status=200&Content-Type=application%2Fxml&body=%3C%3Fxml%20version%3D%221.0%22%20encoding%3D%22UTF-8%22%3F%3E%0A%3Cone%3Etwo%3C%2Fone%3E" # Arachne\Context\ArachneContext::iAccessTheResourceUrl()
    And I use the "one_two.xml" file as request body                                                                                                                                # Arachne\Context\ArachneContext::iUseTheFileAsRequestBody()
    And I set the header "Accept" to "application/vnd.arachne.v1"                                                                                                                   # Arachne\Context\ArachneContext::iSetTheHeaderTo()
    And I send the request                                                                                                                                                          # Arachne\Context\ArachneContext::iSendTheRequest()
    Then the status code should be 200                                                                                                                                              # Arachne\Context\ArachneContext::theStatusCodeShouldBe()
    And response should be a valid XML                                                                                                                                              # Arachne\Context\ArachneContext::responseShouldBeAValidXml()
    And response should validate against "one_two.xsd" schema                                                                                                                       # Arachne\Context\ArachneContext::responseShouldValidateAgainstSchema()
    And response should be identical to "one_two.xml" file                                                                                                                          # Arachne\Context\ArachneContext::responseShouldBeIdenticalToFile()

3 scenarios (3 passed)
24 steps (24 passed)
0m1.47s (10.30Mb)
```

TODO
====

* Allow mutation of http client in the hooks. Currently the hooks are static and they do not have access to the
  context instance.
  
License
=======

```
The MIT License (MIT)

Copyright (c) 2015 Wojtek Gancarczyk <wojtek@aferalabs.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```