# YouTube Summarizer Web App

This is the plan to create a YouTube Summarizer Web App - based on Tempest Framework (PHP)

## Requirements

### Frontend

For the frontend, the following requirements exist
- Light layout with red gradient background in the first section
- Call-To-Action to Summarize YouTube Videos in the first section
- Rounded div in first section containing 
    - Rounded input URL for entering the YouTube Video URL
    - Rounded Dropdown for entering choosing the summary type: Structured, Bulletpoints, Quick
    - Dropdown for language
    - Inside the input URL a clipboard icon to paste from clipboard
    - The design of all three input need to be carefully designed according to UI/UX friendliness
- Section after rounded div appears the summary, once the user clicked on the button
    - See Section 'Summary Frontend'
- Second section features and statistics
    - Three columns next to each other, each containing a bold text and icon above bold text. Use for the image https://placehold.co/128x128 and an Alt-Tag for the Icon Name; these will be replaced by a human later
    - Furthermore, statistics are shown of how many videos have been already summarized
- Third section frequently asked questions
    - Collapsible sections with the question at the beginning and scaffolding answer
    - Collapsible section are rounded
    - Plus/Minus are red colored
- Fourth section Footer

### Backend

#### Framework

We make use of Tempest v1.5.1. The developer need to carefully know the whole documentation and is able to apply it

#### Database

For the database, we must use of Tempest integrated SQLite database (https://tempestphp.com/docs/essentials/database)

The developer need to carefully think about the design of the database

Some consideration:
- Cache YouTube videos summaries for subsequent request based on URL (or rather video ID), summary type
- Limit the number of user request to 10 daily. Limit should be done on IP address, fingerprinting. Unless, Tempest Framework provides an out-of-the box solution

#### YouTube Summarization

To summarize YouTube videos, we make use of the API:
```php
<?php

$client = new \GuzzleHttp\Client();

$response = $client->request('POST', 'https://youtube-video-ai-summarizer-api.p.rapidapi.com/summarize?url=https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3DdQw4w9WgXcQ', [
	'body' => '{"key1":"value","key2":"value"}',
	'headers' => [
		'Content-Type' => 'application/json',
		'x-rapidapi-host' => 'youtube-video-ai-summarizer-api.p.rapidapi.com',
		'x-rapidapi-key' => '<truncated>',
	],
]);

echo $response->getBody();
```

with the (GET) parameters:
- url: Link to YouTube video, either in https://youtube.com/watch?v=dQw4w9WgXcQ or https://youtu.be/dQw4w9WgXcQ
- Language of the Summary, by default english Optional, can be 'en', 'es', 'fr', 'de', 'zh', 'ja', 'ru', 'pt', 'ar', 'hi', 'it', 'ko', 'nl', 'tr', 'pl', 'sv', 'da', 'no', 'fi' Use 'automatic' for automatic language detection
- type: Type of summarization length can be either 'text', 'bullets', 'structured' or 'quick' (we make use of bullets, structured or quick)

While the implementation example is in GuzzleHTTP, it can be created using Tempest Framework libraries if they're part of the box.

## Statistics

For the frontend statistics amke use of Cache (https://tempestphp.com/docs/features/cache)

## Summary Frontend

For the summary part of the frontend-backend, the developer needs to find a way that once the user clicked on 'Summarize' that the page isn't submitted as usual via Form POST, but using JQuery/AJAX or an other way. Tempest Framework provides Asset Bundling (https://tempestphp.com/docs/features/asset-bundling).

Workflow:
User clicks on Summarize ---> Window scroll to Summary section: A red info indicating Summary is generated via a spinning animation --> Once Summary finish, update the Summary Section

Note that there are three different types of summaries:
- Quick: Text _as is_
- Bulletpoints: Bulletpoint Summary, delimited with * or - 
- Structured: Markdown document with headings (##, ###, ...) and markdown syntax for bolding text, make text italic

Therefore, the frontend must deal with the conversion of markdown and bulletpoints as well.

## Documentation

### Tempest Framework

We use Tempest Framework whose documentation is located at https://tempestphp.com/docs/getting-started/introduction. Other pages can be crawled recursively
