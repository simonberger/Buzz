<?php

namespace Buzz\Listener\History;

use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;

class Journal implements \Countable, \IteratorAggregate
{
    private $entries = [];
    private $limit = 10;

    /**
     * Records an entry in the journal.
     *
     * @param RequestInterface $request  The request
     * @param MessageInterface $response The response
     * @param integer          $duration The duration in seconds
     */
    public function record(RequestInterface $request, MessageInterface $response, $duration = null)
    {
        $this->addEntry(new Entry($request, $response, $duration));
    }

    public function addEntry(Entry $entry)
    {
        array_push($this->entries, $entry);
        $this->entries = array_slice($this->entries, $this->getLimit() * -1);
        end($this->entries);
    }

    public function getEntries()
    {
        return $this->entries;
    }

    public function getLast()
    {
        return end($this->entries);
    }

    public function getLastRequest()
    {
        return $this->getLast()->getRequest();
    }

    public function getLastResponse()
    {
        return $this->getLast()->getResponse();
    }

    public function clear()
    {
        $this->entries = [];
    }

    public function count(): int
    {
        return count($this->entries);
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator(array_reverse($this->entries));
    }
}
