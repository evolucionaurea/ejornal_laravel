<?php

return [
  'base_url' => env('QBI2_BASE_URL'),
  'client_app_id' => (int) env('QBI2_CLIENT_APP_ID', 0),
  'token' => env('QBI2_TOKEN'),
  'timeout' => (int) env('QBI2_TIMEOUT', 20),
];
