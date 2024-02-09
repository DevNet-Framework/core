<?php

/**
 * @author      Mohammed Moussaoui
 * @license     MIT license. For more license information, see the LICENSE file in the root directory.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Endpoint\Results;

use DevNet\System\IO\FileAccess;
use DevNet\System\IO\FileException;
use DevNet\System\IO\FileMode;
use DevNet\System\IO\FileStream;
use DevNet\System\MethodTrait;
use DevNet\Web\Endpoint\ActionContext;
use DevNet\Web\Endpoint\IActionResult;

use function Devnet\System\await;

class FileResult implements IActionResult
{
    use MethodTrait;

    private string $path;
    private string $contentType = "application/octet-stream";

    public function __construct(string $path, string $contentType = null)
    {
        if (!file_exists($path)) {
            throw new FileException("Could not find the file {$path}", 0, 1);
        }

        $this->path = $path;
        if ($contentType) {
            $this->contentType = $contentType;
        }
    }

    public function async_invoke(ActionContext $actionContext): void
    {
        $filename = basename($this->path);
        $httpContext = $actionContext->HttpContext;
        $httpContext->Response->Headers->add("Content-Type", $this->contentType);
        $httpContext->Response->Headers->add("Content-Disposition", "attachment;filename={$filename}");
        $httpContext->Response->Body->truncate(0);
        
        $file = new FileStream($this->path, FileMode::Open, FileAccess::Read);
        while (!$file->EndOfStream) {
            $line = await($file->readLineAsync());
            await($httpContext->Response->writeAsync($line));
        }

        $file->close();
    }
}
