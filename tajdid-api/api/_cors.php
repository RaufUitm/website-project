<?php
// Central CORS helper — include before outputting anything
// Remove any existing CORS headers then set a single authoritative set.
if (!headers_sent()) {
    // Remove common CORS headers to avoid duplicates
    header_remove('Access-Control-Allow-Origin');
    header_remove('Access-Control-Allow-Methods');
    header_remove('Access-Control-Allow-Headers');
}

// Allow all origins for local development. Change before production.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Short-circuit preflight requests
    http_response_code(204);
    exit;
}
