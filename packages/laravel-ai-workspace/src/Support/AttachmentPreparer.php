<?php

namespace AiWorkspace\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttachmentPreparer
{
    public function fromRequest(Request $request, string $input = 'files'): array
    {
        $attachments = [];

        if (! $request->hasFile($input)) {
            return $attachments;
        }

        foreach ($request->file($input) as $file) {
            $path = $file->store(
                config('ai-workspace.upload_path', 'uploads/chats'),
                config('ai-workspace.disk', 'public')
            );

            $mime = $file->getMimeType() ?: 'application/octet-stream';
            $attachment = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime' => $mime,
                'size' => $file->getSize(),
                'type' => $this->detectType($mime, $file->getClientOriginalExtension()),
                'url' => asset('storage/' . $path),
            ];

            if ($attachment['type'] === 'image') {
                $attachment['base64'] = base64_encode(file_get_contents($file->getRealPath()));
            }

            if ($attachment['type'] === 'document') {
                $attachment['text_content'] = $this->extractDocumentText(
                    $file->getRealPath(),
                    $file->getClientOriginalExtension()
                );
            }

            $attachments[] = $attachment;
        }

        return $attachments;
    }

    public function detectType(string $mime, string $extension): string
    {
        if (str_starts_with($mime, 'image/')) {
            return 'image';
        }

        if (str_starts_with($mime, 'video/')) {
            return 'video';
        }

        if (str_starts_with($mime, 'audio/')) {
            return 'audio';
        }

        if (in_array(strtolower($extension), config('ai-workspace.document_extensions', []), true)) {
            return 'document';
        }

        return 'file';
    }

    public function extractDocumentText(string $path, string $extension): ?string
    {
        $extension = strtolower($extension);

        if (! in_array($extension, config('ai-workspace.document_extensions', []), true)) {
            return null;
        }

        $contents = @file_get_contents($path);

        if (! is_string($contents)) {
            return null;
        }

        return Str::limit(trim($contents), config('ai-workspace.document_context_limit', 12000));
    }
}
