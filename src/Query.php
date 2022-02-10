<?php

namespace PH7\SearchAi;

// Set up a client with your API key.

use SiteOrigin\OpenAI\Client;
use SiteOrigin\OpenAI\Engines;
use Dotenv\Dotenv;

$requiredEnvFields = [
  'OPENAI_API_KEY'
];

$env = Dotenv::createImmutable(__DIR__);
$env->load();
$env->required($requiredEnvFields)->notEmpty();

$client = new Client($_ENV['OPENAI_API_KEY']);

// Create a completion call
$c = $client->completions(Engines::BABBAGE)->complete('The meaning of life is: ', [ /* ... */]);

// List all the available engines
$e = $client->engines()->list();

// Perform a search
$r = $client->search(Engines::ADA)->search('President', [
    "White House","hospital","school"
]);
$r = $client->search('curie')->search('President', 'the-file-id');

// Request an Answer
$documents = [
    "Puppy named Bailey is happy.",
    "Puppy named Bella is sad.",
];
$a = $client->answers(Engines::CURIE)->create(
    'Which puppy is happy?',
    $documents, // Or a file-id
    'In 2017, U.S. life expectancy was 78.6 years.',
    [["What is human life expectancy in the United States?","78 years."]],
    ["max_tokens" => 5, "stop" => ["\n", "<|endoftext|>"] ]
);

// Request a Classification
$c = $client->classifications(Engines::BABBAGE)->create(
    'It is a raining day :(',
    [["A happy moment", "Positive"],["I am sad.", "Negative"],["I am feeling awesome", "Positive"]]
);
$c = $client->classifications()->create("I'm so happy to be alive", 'the-file-id');

// Classify safe/unsafe content
$f = $client->filter()->classify('ponies are fantastic!'); // 'safe'
