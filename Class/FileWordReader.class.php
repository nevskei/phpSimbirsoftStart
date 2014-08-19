<?php

/**
 * Interface FileWordReaderInterface
 */
interface FileWordReaderInterface
{
    public function currentWord();

    public function nextWord();

    public function resetWord();

    public function openFile();
}

/**
 * Class FileWordReader
 *
 * Class to read the text word by word file
 *
 * @author Group 4
 * @version 1.0
 *
 * @implements FileWordReaderInterface
 */
class FileWordReader implements FileWordReaderInterface
{
    /** @var string string of separators */
    public $separator;
    /** @var string file name to open */
    public $filename;
    /** @var string current word of file */
    private $current_word;

    /**
     * Constructor
     *
     * @param string $filename file name to open
     * @param string $separator default separator "space"
     *
     */
    public function __construct($filename, $separator = " ")
    {
        $this->separator = $separator;
        $this->filename = $filename;
    }

    /**
     * @param null
     * @return string return the contents of the entire file
     */
    public function __toString()
    {
        return "";
    }

    /**
     * Open file for read
     *
     * @param null
     */
    public function openFile()
    {

    }

    /**
     * Put current word of the file
     *
     * @param null
     */
    public function currentWord()
    {

    }

    /**
     * Put next word of the file
     *
     * @param null
     */
    public function nextWord()
    {

    }

    /**
     * Reset index default
     * @param null
     */
    public function resetWord()
    {

    }

} 