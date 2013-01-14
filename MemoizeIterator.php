<?php
class MemoizeIterator extends CachingIterator
{
    protected $cache = null;
    protected $cache_pos = null;
    protected $cache_size = null;
    protected $already_rewinded = false;

    public function __construct(Iterator $iter) {
        parent::__construct($iter, CachingIterator::FULL_CACHE);
    }

    public function rewind() {
        if (!$this->already_rewinded) {
            parent::rewind();
            $this->already_rewinded = true;
        } else {
            $this->cache = $this->getCache();
            $this->cache_pos = 0;
            $this->cache_size = count($this->cache);
        }
        return;
    }
 
    public function valid() {
        if ($this->cache !== null) {
            return true;
        }
        return parent::valid();
    }
 
    public function current() {
        if ($this->cache !== null) {
            return current($this->cache);
        }
        return parent::current();
    }
 
    public function key() {
        if ($this->cache !== null) {
            return key($this->cache);
        }
        return parent::key();
    }
 
    public function next() {
        if ($this->cache !== null) {
            $this->cache_pos++;
            if ($this->cache_pos < $this->cache_size) {
                next($this->cache);
                return;
            } else {
                $this->cache = $this->cache_pos = $this->cache_size = null;
            }
        }
        parent::next();
        return;
    }
}
