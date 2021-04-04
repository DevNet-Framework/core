<?php declare(strict_types = 1);
/**
 * @author      Mohammed Moussaoui
 * @copyright   Copyright (c) Mohammed Moussaoui. All rights reserved.
 * @license     MIT License. For full license information see LICENSE file in the project root.
 * @link        https://github.com/DevNet-Framework
 */

namespace DevNet\Web\Http;

class Stream
{
    public $Resource;
    public $Position = 0;

    public function __construct(string $filename, string $mode)
    {
        $this->Resource = fopen($filename, $mode);
    }

    public function getSize()
    {
        if (null === $this->Resource)
        {
            return null;
        }

        $stats = fstat($this->Resource);
        
        if ($stats !== false)
        {
            return $stats['size'];
        }

        return null;
    }

    public function isSeekable() : bool
    {
        if (! $this->Resource)
        {
            return false;
        }

        $meta = stream_get_meta_data($this->Resource);
        return $meta['seekable'];
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        if (! $this->Resource)
        {
            throw new \Exception("Missing Resource");
        }

        if (! $this->isSeekable())
        {
            throw new \Exception("Resource is not seekable");
        }

        $result = fseek($this->Resource, $offset, $whence);

        return $result;
    }
    
    public function isReadable() : bool
    {
        if (! $this->Resource)
        {
            return false;
        }

        $meta = stream_get_meta_data($this->Resource);
        $mode = $meta['mode'];

        return (strstr($mode, 'r') || strstr($mode, '+'));
    }

    public function read(int $buffer = null)
    {
        if (! $this->Resource)
        {
            throw new \Exception('Missing resource');
        }

        if (! $this->isReadable())
        {
            throw new \Exception ('Not readable');
        }

        if ($buffer == null)
        {
            $buffer = $this->getSize();
        }

        $result = fread($this->Resource, $buffer);

        if (false === $result)
        {
            throw new \Exception('Unable to read from resource');
        }

        return $result;
    }

    public function isWritable() : bool
    {
        if (! $this->Resource)
        {
            return false;
        }

        $meta = stream_get_meta_data($this->Resource);
        $mode = $meta['mode'];

        return (
            strstr($mode, 'x')
            || strstr($mode, 'w')
            || strstr($mode, 'c')
            || strstr($mode, 'a')
            || strstr($mode, '+')
        );
    }

    public function write(string $string) : int
    {
        if (! $this->Resource)
        {
            throw new \Exception('Missing resource');
        }

        if (! $this->isWritable())
        {
            throw new \Exception('not writible');
        }

        $result = fwrite($this->Resource, $string);

        if (false === $result)
        {
            throw new \Exception('Unable to write to resource');
        }

        return $result;
    }

    public function eof() : bool
    {
        if (! $this->Resource)
        {
            return true;
        }

        return feof($this->Resource);
    }

    public function close() : void
    {
        if ($this->Resource)
        {
            fclose($this->Resource);
        }
    }

    public function __toString()
    {
        if (!$this->isSeekable())
        {
            return '';
        }

        if (!$this->isReadable())
        {
            return '';
        }

        $this->seek(0);
        $result = stream_get_contents($this->Resource);

        if ($result == false)
        {
            throw new \Exception("Unable to read from the stream");
        }
        
        return $result;
    }
}
