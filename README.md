# Laravel AI Workspace

A modern, fast, and interactive AI workspace built with Laravel. This application allows users to interact with AI (via Google Gemini) through a robust dashboard that supports rich text formats, file attachments, and real-time streaming responses.

## Key Features

- **Real-Time Streaming**: Delivers AI responses as they are generated.
- **Contextual Chat History**: Remembers your previous topics. Grouped intelligently (Today, Yesterday, Last 7 Days, etc.).
- **Markdown & Code Support**: Fully parses and highlights Markdown formatting and inline code correctly.
- **Document Attachments**: Supports uploading files (`.txt`, `.md`, `.json`, `.csv`, `.log`, `.xml`, and images/media) within a prompt context.
- **Dark Mode Support**: Comes with a sleek Dark/Light toggle for user convenience.
- **Beautiful UI**: Glassmorphism, animations, and gradients designed to be user-friendly and aesthetically pleasing.

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL / MariaDB (or SQLite)
- Google Gemini API Key

## Setup & Installation

Follow these steps to run the service locally:

1. **Clone & Install Dependencies**
```bash
git clone <repository-url>
cd laravel-ai
composer install
npm install
```

2. **Environment Variables**
Copy the `.env.example` file to `.env`:
```bash
cp .env.example .env
```

3. **Configure API Key & Database**
Open your `.env` file and set up your database credentials. Most importantly, add your Gemini API Key at the bottom:
```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_ai
DB_USERNAME=root
DB_PASSWORD=

# Your Google Gemini API Key
GEMINI_API_KEY="AIzaSy...your-api-key"
```

4. **Generate App Key & Run Migrations**
```bash
php artisan key:generate
php artisan migrate
```

5. **Build Assets**
Compile the frontend assets (Tailwind CSS, basic js):
```bash
npm run build
# OR for development:
npm run dev
```

6. **Serve the Application**
Start the Laravel local development server:
```bash
php artisan serve
# Go to http://127.0.0.1:8000
```

## How to Use

1. Navigate to the app URL (`http://localhost:8000`).
2. Register a new account or Login if you already have one.
3. You will be redirected to the **AI Workspace Dashboard**.
4. Use the input area at the bottom to send messages. Click the attachment (`📎`) icon if you need to upload a document context or an image.
5. In the left sidebar, click `+ Chat Baru` to start a completely new session without the previous conversation memory. Use `Rename` and `Hapus` to manage the chat history.

## Troubleshooting

- **ERR_TOO_MANY_REDIRECTS**: Occurs usually if the route definitions are missing the 'web' middleware. Ensure `/dashboard` route is defined correctly under the `auth` middleware group in `routes/web.php`.
- **Response stream stops halfway**: Verify that your API key is active or that your `.env` `GEMINI_API_KEY` is properly defined.
