<?php
/*
 * GorKa Team
 * Copyright (c) 2023  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

use App\WebSockets\CustomWebSocketHandler;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;

/*
|--------------------------------------------------------------------------
| WebSockets Handlers
|--------------------------------------------------------------------------
*/

WebSocketsRouter::webSocket('/app/{appKey}', CustomWebSocketHandler::class);
