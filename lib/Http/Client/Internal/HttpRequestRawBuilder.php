<?php

/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http\Client\Internal;

use DevNet\System\Text\StringBuilder;
use DevNet\Web\Http\HttpRequest;

class HttpRequestRawBuilder
{
    public static function build(HttpRequest $request): string
    {
        $requestRaw = new StringBuilder();
        $requestRaw->append($request->Method);
        $requestRaw->append(' ');
        $requestRaw->append($request->Uri->Path);
        $requestRaw->append(' ');
        $requestRaw->append($request->Protocol);
        $requestRaw->append("\r\n");

        foreach ($request->Headers->getAll() as $key => $values) {
            foreach ($values as $value) {
                $requestRaw->append($key);
                $requestRaw->append(': ');
                $requestRaw->append($value);
                $requestRaw->append("\r\n");
            }
        }
        
        $requestRaw->append("\r\n");
        if ($request->Body->IsReadable) {
            if ($request->Body->Length > 0) {
                $requestRaw->appendLine($request->Body->read($request->Body->Length));
            }
        }

        return $requestRaw;
    }
}
