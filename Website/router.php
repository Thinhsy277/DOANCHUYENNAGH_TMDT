<?php
/**
 * Router script for PHP built-in server
 * This file handles static files and routes all other requests to index.php
 */

// Get the request URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = urldecode($uri);

// Remove query string
$uri = strtok($uri, '?');

// Normalize URI
$uri = '/' . ltrim($uri, '/');

// Define static file extensions
// NOTE: 'html' is NOT included because CodeIgniter uses .html as URL suffix
$staticExtensions = [
    'css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'ico', 'svg', 
    'woff', 'woff2', 'ttf', 'eot', 'pdf', 'zip', 'map', 'json'
];

$extension = strtolower(pathinfo($uri, PATHINFO_EXTENSION));

// Skip .html files - these are CodeIgniter routes, not static files
if ($extension === 'html') {
    // Remove .html suffix for CodeIgniter routing
    $uri = preg_replace('/\.html$/', '', $uri);
    $extension = '';
}

// If it's a static file, try to serve it
if ($extension && in_array($extension, $staticExtensions)) {
    // Try different possible paths
    $baseDir = __DIR__;
    
    // Path 1: Direct path (e.g., /public/css/style.css)
    $filePath = $baseDir . $uri;
    
    // Path 2: If URI doesn't start with public/, add it (e.g., /css/style.css -> /public/css/style.css)
    if (!file_exists($filePath) && strpos($uri, '/public/') !== 0) {
        $filePath = $baseDir . '/public' . $uri;
    }
    
    // Path 3: If still not found and URI starts with /public/, try without /public/ prefix
    if (!file_exists($filePath) && strpos($uri, '/public/') === 0) {
        $filePath = $baseDir . $uri;
    }
    
    // If file exists, serve it
    if (file_exists($filePath) && is_file($filePath)) {
        // Set MIME types
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'ico' => 'image/x-icon',
            'svg' => 'image/svg+xml',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
            'pdf' => 'application/pdf',
            'zip' => 'application/zip',
            'map' => 'application/json'
        ];
        
        $mimeType = isset($mimeTypes[$extension]) 
            ? $mimeTypes[$extension] 
            : 'application/octet-stream';
        
        // Set headers
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        
        // Output file
        readfile($filePath);
        exit;
    }
}

// For all other requests, route to index.php
// Set proper environment variables for CodeIgniter
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Set PATH_INFO for CodeIgniter
// Remove leading slash and set PATH_INFO
$pathInfo = ltrim($uri, '/');
if ($pathInfo && $pathInfo !== 'index.php') {
    $_SERVER['PATH_INFO'] = '/' . $pathInfo;
} else {
    $_SERVER['PATH_INFO'] = '/';
}

// Also set REQUEST_URI if not set
if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = $uri;
}

// Route to CodeIgniter
require __DIR__ . '/index.php';
