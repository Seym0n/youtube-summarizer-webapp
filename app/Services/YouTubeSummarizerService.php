<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\VideoSummary;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

use function Tempest\Database\query;

final class YouTubeSummarizerService
{
    private const API_URL = 'https://youtube-video-ai-summarizer-api.p.rapidapi.com/summarize';
    private const API_HOST = 'youtube-video-ai-summarizer-api.p.rapidapi.com';

    public function __construct(
        private readonly Client $httpClient,
        private readonly string $rapidApiKey
    ) {
    }

    public function getSummary(string $youtubeUrl, string $summaryType, string $language): array
    {
        $videoId = $this->extractVideoId($youtubeUrl);
        
        // Check cache first
        $cachedSummary = $this->getCachedSummary($videoId, $summaryType, $language);
        if ($cachedSummary !== null) {
            return [
                'success' => true,
                'summary' => $cachedSummary->summary_content,
                'cached' => true
            ];
        }

        try {
            $response = $this->httpClient->request('POST', self::API_URL, [
                'query' => [
                    'url' => $youtubeUrl,
                    'language' => $language,
                    'type' => $this->mapSummaryType($summaryType)
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'x-rapidapi-host' => self::API_HOST,
                    'x-rapidapi-key' => $this->rapidApiKey,
                ],
                'body' => '{}' // Empty body as per API spec
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['summary'])) {
                $summaryContent = $data['summary'];
                
                // Cache the summary
                $this->cacheSummary($videoId, $youtubeUrl, $summaryType, $language, $summaryContent);
                
                return [
                    'success' => true,
                    'summary' => $summaryContent,
                    'cached' => false
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Invalid API response'
                ];
            }
        } catch (RequestException $e) {
            return [
                'success' => false,
                'error' => 'API request failed: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Unexpected error: ' . $e->getMessage()
            ];
        }
    }

    private function extractVideoId(string $youtubeUrl): string
    {
        // Handle youtube.com/watch?v=VIDEO_ID format
        if (preg_match('/[?&]v=([^&]+)/', $youtubeUrl, $matches)) {
            return $matches[1];
        }
        
        // Handle youtu.be/VIDEO_ID format
        if (preg_match('/youtu\.be\/([^?]+)/', $youtubeUrl, $matches)) {
            return $matches[1];
        }
        
        throw new Exception('Invalid YouTube URL format');
    }

    private function mapSummaryType(string $summaryType): string
    {
        return match ($summaryType) {
            'structured' => 'structured',
            'bulletpoints' => 'bullets',
            'quick' => 'quick',
            default => 'quick'
        };
    }

    private function getCachedSummary(string $videoId, string $summaryType, string $language): ?VideoSummary
    {
        $result = query(VideoSummary::class)
            ->select()
            ->where('video_id = ? AND summary_type = ? AND language = ?', $videoId, $summaryType, $language)
            ->first();

        return $result;
    }

    private function cacheSummary(string $videoId, string $url, string $summaryType, string $language, string $summaryContent): void
    {
        query(VideoSummary::class)
            ->insert(
                video_id: $videoId,
                url: $url,
                summary_type: $summaryType,
                language: $language,
                summary_content: $summaryContent,
                created_at: new DateTime()
            )
            ->execute();
    }
}