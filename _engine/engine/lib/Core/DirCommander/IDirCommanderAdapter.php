<?php

namespace lib\Core\DirCommander;

/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 27.10.12
 * Time: 1:27
 * To change this template use File | Settings | File Templates.
 */
interface IDirCommanderAdapter {

  /**
   * Copy function.
   *
   * @param string $source Source file to copy.
   * @param string $destination Target path.
   *
   * @return boolean True on success.
   */
  public function copy($source, $destination);

  /**
   * File_put_contents function.
   *
   * @param string $filename Source file to copy.
   * @param string|array|resource $data Data to write.
   * @param int $flags Flags.
   *
   * @return integer|boolean Number of bytes written on success, false on fail.
   */
  public function putContents($filename, $data, $flags = 0);

  /**
   * Reads entire file into a string
   *
   * @param string $filename Name of the file to read.
   *
   * @return string|boolean The function returns the read data or FALSE on failure.
   */
  public function getContents($filename);

  /**
   * Facade for the chmod function.
   *
   * @param string $filename Source file to chmod.
   * @param integer $mode Perm to chmod to.
   *
   * @return boolean True on success.
   */
  public function chmod($filename, $mode);

  /**
   * Scandir function.
   *
   * @param string $directory Directory to scan.
   * @param integer $sorting_order Sort the contents?.
   *
   * @return array|boolean Array of contents on success, false on failure.
   */
  public function scandir($directory, $sorting_order = 0);

  /**
   * Makes directory
   *
   * @param string $pathname The directory path.
   * @param int $mode The mode is 0777 by default, which means the widest possible access. For more information on modes, read the details on the chmod() page.
   * @param bool $recursive Allows the creation of nested directories specified in the pathname.
   *
   * @return boolean Returns TRUE on success or FALSE on failure.
   */
  public function mkdir($pathname, $mode = 0777 , $recursive = false);

  /**
   * Recursive delete file or directory
   *
   * @param string $path
   *
   * @return boolean True on success.
   */
  public function remove($path);

  /**
   * Rename function.
   *
   * @param string $oldname Old path to rename.
   * @param string $newname New path.
   *
   * @return boolean True on success.
   */
  public function rename($oldname, $newname);

  /**
   *
   * @param string $filename Path to check
   *
   * @return boolean True if is writable False if not.
   */
  public function isWritable($filename);

  /**
   * is_readable function.
   *
   * @param string $filename Path to check
   *
   * @return boolean True if is writable False if not.
   */
  public function isReadable($filename);

  /**
   * Checks whether a file or directory exists.
   *
   * @param string $filename Path to the file or directory.
   *
   * @return boolean Returns TRUE if the file or directory specified by filename exists; FALSE otherwise.
   */
  public function isExist($filename);
  /**
   * is dir function.
   *
   * @param string $path Path to check
   *
   * @return boolean True if is dir path False if not.
   */
  public function isDir($path);

  /**
   * is file function.
   *
   * @param string $path Path to check
   *
   * @return boolean True if is file path False if not.
   */
  public function isFile($path);

}
