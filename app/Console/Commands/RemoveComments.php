<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoveComments extends Command
{
    protected $signature = 'remove:comments {file : The path to the PHP file}';
    protected $description = 'Remove all comments from a specified PHP file';

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return 1;
        }

        $content = file_get_contents($filePath);
        $newContent = $this->removeComments($content);

        if ($content !== $newContent) {
            file_put_contents($filePath, $newContent);
            $this->info("Comments removed from: $filePath");
            return 0;
        }

        $this->info("No comments found in: $filePath");
        return 0;
    }

    private function removeComments(string $content): string
    {
        // Remove single-line comments (//)
        $content = preg_replace('/\/\/.*$/m', '', $content);
        
        // Remove multi-line comments (/* ... */)
        $content = preg_replace('/\/\*.*?\*\//s', '', $content);
        
        // Remove docblocks (/** ... */)
        $content = preg_replace('/\/\*\*.*?\*\//s', '', $content);
        
        // Clean up empty lines left after removal
        $content = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $content);
        
        return trim($content);
    }
}