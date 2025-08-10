# YouTube Video Summarizer

<img width="1904" height="895" alt="image" src="https://github.com/user-attachments/assets/160de70d-caf1-4ea8-b980-a3b3fc6b55f4" />


A modern web application built with [Tempest PHP Framework](https://tempestphp.com) that generates AI-powered summaries of YouTube videos in multiple languages and formats.

## Features

- **Multiple Summary Types**: Quick, Bullet Points, and Structured summaries
- **Multi-language Support**: 19+ languages with automatic browser language detection
- **Rate Limiting**: 10 requests per day per IP address to prevent abuse
- **Smart Caching**: Prevents duplicate API calls for the same video
- **Modern UI**: Responsive design based on Tailwind
- **Clipboard Integration**: One-click URL pasting from clipboard
- **Searchable Language Dropdown**: Easy language selection with search functionality

## Tech Stack

- **Backend**: Tempest PHP Framework v1.2
- **Database**: SQLite with migrations
- **Frontend**: Vanilla JavaScript with Tailwind CSS
- **Build Tool**: Vite for asset bundling
- **API**: RapidAPI's YouTube Video AI Summarizer

## Requirements

- PHP 8.4 or above
- NodeJS 22 or above

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd youtube-summarizer-webapp
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Set up environment variables:
Create a `.env` file with:
```bash
RAPIDAPI_KEY=your_rapidapi_key_here
```

Get your API Key from <a href="https://rapidapi.com/Seymon/api/youtube-video-ai-summarizer-api">YouTube Video AI Summarizer API</a>

5. Run database migrations:
```bash
./tempest migrate
```

> [!NOTE]  
> SQLite database is used

6. Build assets:
```bash
npm run build
```

7. Start the development server:
```bash
./tempest serve
```

## Environment Variables

- `RAPIDAPI_KEY`: Your RapidAPI key for the YouTube Video AI Summarizer API

## Database Schema

### video_summaries
- `video_id`: YouTube video ID
- `url`: Full YouTube URL
- `summary_type`: Type of summary (quick, bulletpoints, structured)
- `language`: Language code for the summary
- `summary_content`: The generated summary
- `created_at`: Timestamp

### request_limits
- `ip_address`: Client IP address
- `request_count`: Number of requests made
- `last_request_date`: Date of last request

## API Endpoints

- `GET /`: Home page with summarizer interface
- `POST /api/summarize`: Generate video summary
  - Parameters: `url`, `type`, `language`
  - Returns: JSON with summary content and metadata

## Supported Languages

The application supports 19+ languages including:
- English, Spanish, French, German, Italian
- Portuguese, Russian, Chinese, Japanese, Korean
- Arabic, Hindi, Dutch, Swedish, Norwegian
- Danish, Finnish, Polish, Turkish, Hebrew

## Rate Limiting

- 10 requests per day per IP address
- Counts reset daily at midnight
- Cached summaries don't count against the limit

## Development

Start development mode:
```bash
# Terminal 1: Start PHP server
./tempest serve

# Terminal 2: Watch assets
npm run dev
```

<a href="https://rapidapi.com/Seymon/api/youtube-video-ai-summarizer-api" target="_blank">
	<img src="https://storage.googleapis.com/rapidapi-documentation/connect-on-rapidapi-dark.png" width="215" alt="Connect on RapidAPI">
</a>
