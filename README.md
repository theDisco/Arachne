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
  suites:
    json:
      contexts:
        - Arachne\Context\ArachneContext
```

In order to enable the extension, you need to add it to the extensions node of your config. 

**base_url**

`base_url` is the only required configuration value and will be prepended to all requests made by the extension.
 
**paths.schema_file_dir**

Under the hood Arachne uses [json schema](http://json-schema.org/) validator to validate the structure
of the response. In order to use `response should validate against "one_two" schema`, extensions needs to know, where 
to find schema files. `paths.schema_file_dir` tells Arachne, which folder are the schema files located in.
In this particular example, Arachne will look for a schema in `examples/schemas/one_two.json` file.

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

**paths.request_file_dir**

Sometimes requests are relatively large and their content would make the features unreadable. Therefore
Arachne supports setting request bodies using content of a file. `paths.request_file_dir` tells Arachne,
which folder are the request files located in. In this case, Arachne will look for request body in
`examples/requests/one_two.json` file.
 
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

**paths.response_file_dir**

Similarly as in case of request files, responses delivered by the webservice might be relatively large.
In order to validate not only the schema of the response, but also it's content, Arachne supports comparing
the content of the response body with content of a file. `paths.response_file_dir` tells Arachne,
which folder are the response files located in. In this case, Arachne will look for a response body in
`examples/responses/one_two.json` file.

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

**auth.priovider**

Some of the webservices require some kind of persistance between the requests. In order to do that you can
use an authentication provider to perform authentication before the test starts and provide its result to the
context. Use this config variable, to tell Arachne to use authentication provider before the test will start.
You can read more about authentication providers below.

Steps
=====

Given/When
----------

**I use ".*" request method**

Sets the request method to the provided http verb.

**I access the resource url ".*"**

Sets the path for the request.

**I use the ".*" file as request body**

Uses the content of the file as a request body.

**I send the request**

Has to be explicitly called to send the request.
 
Then
----

**the status code should be \d+**

Validates, if the returned status code is equal to the expected value.

**response should be a valid JSON**

Validates, if the response body can be deserialized as a valid JSON.

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

How To Run Example
==================

In order to run the examples provided in the repository, follow below steps.

```bash
git clone git@github.com:theDisco/Arachne.git
cd Arachne
composer install
cd examples
../vendor/bin/behat
```

The output should be similar to the one below.

```
Feature: Fake JSON API sample
  In order for extension to work
  As an API user
  I need to be able to interact with Fake JSON API

  Scenario:                                     # features/example.feature:6
    Given I use "GET" request method            # Arachne\Context\ArachneContext::iUseRequestMethod()
    When I access the resource url "/key/value" # Arachne\Context\ArachneContext::iAccessTheResourceUrl()
    And I send the request                      # Arachne\Context\ArachneContext::iSendTheRequest()
    Then the status code should be 200          # Arachne\Context\ArachneContext::theStatusCodeShouldBe()
    And response should be a valid JSON         # Arachne\Context\ArachneContext::responseShouldBeAValidJson()

  Scenario:                                                       # features/example.feature:13
    Given I use "GET" request method                              # Arachne\Context\ArachneContext::iUseRequestMethod()
    When I access the resource url "/one/two"                     # Arachne\Context\ArachneContext::iAccessTheResourceUrl()
    And I send the request                                        # Arachne\Context\ArachneContext::iSendTheRequest()
    Then the status code should be 200                            # Arachne\Context\ArachneContext::theStatusCodeShouldBe()
    And response should be a valid JSON                           # Arachne\Context\ArachneContext::responseShouldBeAValidJson()
    And response header "Server" should contain "Google Frontend" # Arachne\Context\ArachneContext::responseHeaderShouldContain()
    And response should validate against "one_two" schema         # Arachne\Context\ArachneContext::responseShouldValidateAgainstSchema()

  Scenario:                                            # features/example.feature:22
    Given I use "POST" request method                  # Arachne\Context\ArachneContext::iUseRequestMethod()
    When I access the resource url "/one/two"          # Arachne\Context\ArachneContext::iAccessTheResourceUrl()
    And I use the "one_two" file as request body       # Arachne\Context\ArachneContext::iUseTheFileAsRequestBody()
    And I send the request                             # Arachne\Context\ArachneContext::iSendTheRequest()
    Then the status code should be 200                 # Arachne\Context\ArachneContext::theStatusCodeShouldBe()
    And response should be a valid JSON                # Arachne\Context\ArachneContext::responseShouldBeAValidJson()
    And response should be identical to "one_two" file # Arachne\Context\ArachneContext::responseShouldBeIdenticalToFile()

3 scenarios (3 passed)
19 steps (19 passed)
0m7.18s (14.47Mb)
```

TODO
====

* Allow mutation of http client in the hooks. Currently the hooks are static and they do not have access to the
  context instance.
* Arachne can be only used to test JSON APIs. Everything is assumed to be JSON. This should be changed and XMLRPCs 
  should also be testable.
  
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