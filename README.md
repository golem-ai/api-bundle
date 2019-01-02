# Golem.ai Symfony bundle

## Installation

`composer install golem-ai/api-bundle`

## Usage

```php
// AcmeController.php

class AcmeController extends Controller {
    public function acmeAction() {
        $serializer = $this->getContainer()->get('golem.serializer');
        $client = $this->getContainer()->get('golem');

        $response = $client->call('https://your.proxy.link', [
            // Mandatory
            'token' => 'yourgolemservertoken',
            'text' => 'Eau sans glaçons',

            // Optionnal
            'type' => RequestData::REQUEST_TYPE,
            'conversation_mode' => false,
            'disable_verbose' => false,
            'labelling' => true,
            'language' => 'fr',
            'multiple_interaction_search' => false,
            'parameters_detail' => false,
        ]);

        $responseObject = $serializer->deserialize($response, Response::class, 'golem_response');

        // Do stuff...
    }
}
```